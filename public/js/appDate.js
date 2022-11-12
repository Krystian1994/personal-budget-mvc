const date = document.querySelector("#actualDate");
function returnDate() {
    const actualDate = new Date();
    let day = actualDate.getDate();
    if (day < 10) day = `0${day}`;
    let month = actualDate.getMonth() + 1;
    if (month < 10) month = `0${month}`;
    let year = actualDate.getFullYear();
    return `${year}-${month}-${day}`;
}

window.addEventListener('load', function (e) {
    date.value = returnDate();
});


