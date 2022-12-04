//wyświetla informacje o limicie czy przekroczony czy nie
const renderOnDOM = (e) => {
    const result = document.querySelector('#limit');
    result.textContent = `Limit wynosi ${e}`;
};

//sprawdza limit dla kategorii z danym ID
const getLimitForCategory = async (category) => {    
    try{
        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        return data;
    } catch(e) {
        console.log('ERROR', e);
    }
};

const selectCategory = document.querySelector('#expenseChange');


selectCategory.addEventListener('change', (event) => {
    
    const category = event.target.value;
    const data = getLimitForCategory(category);
   
    renderOnDOM(data); 
});



