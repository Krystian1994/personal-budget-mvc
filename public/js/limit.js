//wyświetla informacje o limicie czy przekroczony czy nie
const renderLimit= (e) => {
    const result = document.querySelector('#limit');
    result.textContent = `Limit wynosi ${e}`;
};

const renderBalance = (e) => {
    const result = document.querySelector('#balance');
    if(!(isNaN(e))){
        const balance = e.toFixed(2);
        result.textContent = `Bilans ${balance}`;
    }else{
        renderNullBalance();
    }
    
};

const renderNullLimit = () => {
    const result = document.querySelector('#limit');
    result.textContent = null;
};

const renderNullBalance = () => {
    const result = document.querySelector('#balance');
    result.textContent = null;
};

//sprawdza sumę wydatków dla kategorii
const getSumForCategory = async (category, selectDate) => {    
    try{
        const res = await fetch(`../api/expenseSum/${category}/${selectDate}`);
        const sum = await res.json();
        return sum;
    } catch(e) {
        console.log('ERROR', e);
    }
};

//sprawdza limit dla kategorii
const getLimitForCategory = async (category) => {    
    try{
        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        return data;
    } catch(e) {
        console.log('ERROR', e);
    }
};

const calculate = (sum,limit) => {
    if(sum && limit){
        return limit-sum;
    }
};

const eventsSumAction = async (category, limit, selectDate) => {
    if(category && selectDate) {
        const sum = await getSumForCategory(category, selectDate);
        return sum;
    }
};


const eventsLimitAction = async (category) => {
    if(!!category) {
        const limit = await getLimitForCategory(category);
        if(limit>0){
            renderLimit(limit);
            return limit;
        } else {
            renderNullLimit();
        }
    }
};

const selectCategory = document.querySelector('#expenseChange');

selectCategory.addEventListener('change', (s) => {
    const categoryLimit = s.target.value; 
    const returnedValue = eventsLimitAction(categoryLimit); 
});


const selectDate = document.querySelector('#actualDate');

const selectAmount = document.querySelector('#actualAmount');

window.addEventListener('load', () => {
    var date = selectDate.value; 
    var amount = 0.00;
    var category = selectCategory.value;

    selectDate.addEventListener('input', (s) => {
        date = s.target.value; 
        someAction(category);
    });

    selectCategory.addEventListener('change', (s) => {
        category = s.target.value; 
        someAction(category);     
    });
    
    selectAmount.addEventListener('input', (s) => {
        amount = s.target.value; 
        someAction(category); 
    });

    const someAction = async (category) => {
        const limitCat = await eventsLimitAction(category);
        if(!(limitCat<=0)){
            const sumCat = await eventsSumAction(category,limitCat,date);
            if(sumCat>0){
                var balance = calculate(sumCat,limitCat);
                balance -= amount;
                renderBalance(balance);
            }else if(!(sumCat>0) && (amount > 0.00)) {
                var balance = calculate(amount,limitCat);
                renderBalance(balance);
            }else{
                renderNullBalance();
            }
        }
    };


});

