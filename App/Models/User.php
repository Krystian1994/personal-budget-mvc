<?php

namespace App\Models;

use PDO;
use \App\Token;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();
        $this->recaptchaCheck();
        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users SET username = :name, password = :password_hash, email = :email';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->execute();
    
            $userId = $this->getId($password_hash);

            $this->addExpenses($userId);
            $this->addIncomes($userId);
            $this->paymentCategories($userId);
            return true;
        }

        return false;
    }

    private function recaptchaCheck()
    {
         //sprawdzamy recaptche
         $secret = '6LcA9_khAAAAAExJH8XKebdCC0-SwL6ZZh87eSZB';
         //łączymy się z serwerem googla w celu weryfikacji recaptchy
         $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
         //dekodujemy odpowiedź z formatu JSON
         $answer = json_decode($check);
         //sprawdzamy atrybut obiektu answer
         if($answer->success==false){
             $this->errors[] = 'Potwierdź, że nie jesteś botem.';
         }
    }

    protected function getId($password_hash)
    {
        $sql = 'SELECT id FROM users WHERE username = :name AND password = :password_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

        $stmt->execute();

        $userSelected = $stmt->fetch();
        $userId = $userSelected['id'];

        return $userId;
    }

    protected function addExpenses($userId)
    {
        $sql = 'INSERT INTO expenses_category_assigned_to_users(user_id,name) SELECT users.id, expenses_category_default.name FROM users, expenses_category_default WHERE users.id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        return $stmt->execute();
    }

    protected function addIncomes($userId)
    {
        $sql = 'INSERT INTO incomes_category_assigned_to_users(user_id,name) SELECT users.id, incomes_category_default.name FROM users, incomes_category_default WHERE users.id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        return $stmt->execute();
    }

    protected function paymentCategories($userId)
    {
        $sql = 'INSERT INTO payment_methods_assigned_to_users(user_id,name) SELECT users.id, payment_methods_default.name FROM users, payment_methods_default WHERE users.id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

        return $stmt->execute();
    }
    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Imię jest wymagane';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Błędny email';
        }
        if (static::emailExists($this->email)) {
            $this->errors[] = 'Podany email został użyty';
        }

        // Password
        if (strlen($this->password) < 6) {
            $this->errors[] = 'Hasło musi zawierać minimum 6 znaków';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać minimum jedną literę';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać minimum jedną liczbę';
        }
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        //$expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        //$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }
}

