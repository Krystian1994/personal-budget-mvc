//wyświetla informacje o limicie czy przekroczony czy nie
const renderLimit= (e) => {
    const result = document.querySelector('#limit');
    result.textContent = `Limit wynosi ${e}`;
};

const renderBalance = (e) => {
    const result = document.querySelector('#balance');
    result.textContent = `Bilans ${e}`;
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
        if(sum>0){
            const balance = calculate(sum,limit);
            renderBalance(balance);
        }else{
            renderNullBalance();
        }
        
    }
};

// const eventsSumAction = async (category, limit, selectDate) => {
//     if(category && selectDate) {
//         const sum = await getSumForCategory(category, selectDate);
//         return sum;
//     }
// };


const eventsLimitAction = async (category, selectDate) => {
    if(!!category) {
        const limit = await getLimitForCategory(category);
        if(limit>0){
            eventsSumAction(category, limit, selectDate);
            renderLimit(limit);
            return limit;
        } else {
            renderNullLimit();
        }
    }
};


// const eventsLimitAction = async (category) => {
//     if(!!category) {
//         const limit = await getLimitForCategory(category);
//         if(limit>0){
//             renderLimit(limit);
//             return limit;
//         } else {
//             renderNullLimit();
//         }
//     }
// };

const selectCategory = document.querySelector('#expenseChange');

const selectDate = document.querySelector('#actualDate');

const selectAmount = document.querySelector('#actualAmount');

window.addEventListener('load', () => {
    var date = selectDate.value; 
    var amount = 0;
    var category = selectCategory.value;

    selectDate.addEventListener('input', (s) => {
        date = s.target.value; 
    });

    selectCategory.addEventListener('change', (s) => {
        category = s.target.value; 
        renderNullBalance();
        eventsLimitAction(category, date);
        // var limitCat = eventsLimitAction(category);
        // var sumCat = eventsSumAction(category,limitCat,date);
        // if(sumCat>0){
        //     const balance = calculate(sum,limit);
        //     renderBalance(balance);
        // }else{
        //     renderNullBalance();
        // }
        

        selectAmount.addEventListener('input', (s) => {
            amount = s.target.value; 
            // console.log(amount);
            // console.log(limitCat);
        });
    });
});

