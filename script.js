const btn = document.querySelectorAll(".btn");
const accordion = document.querySelectorAll(".accordion");
const form = document.querySelector("#form");
const submitForm = document.querySelector("#submit");

//Accordion function
btn.forEach((btn) => {
    btn.addEventListener('click', (e) => {
        //Open accordion whose button is clicked
        e.currentTarget.parentElement.parentElement.classList.toggle('show');

        //change toggle button text
        (e.currentTarget.innerText == "+")? e.currentTarget.innerText = "-": e.currentTarget.innerText = "+";
    })
})

//Submit form on CTRL + Enter
window.addEventListener('keydown', function(e){
    if(e.ctrlKey && e.keyCode === 13){    //click up arrow key
        submitForm.click();
    }
});