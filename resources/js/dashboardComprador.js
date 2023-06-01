import Choices from "choices.js";

window.onload = function(){
    var choices = new Choices('#filtrosCompradorDashboard #category', {
        placeholderValue: "Selecione uma categoria"
    });

    window.choices = choices;

    document.querySelector('#filtrosCompradorDashboard form').addEventListener('submit', (e) => {
        e.preventDefault();

        let form = document.querySelector("#collapseExample");
    
        let name = form.querySelector('[name="name"]').value;
        let smallerPrice = form.querySelector('[name="smallerPrice"]').value;
        let biggerPrice = form.querySelector('[name="biggerPrice"]').value;
        let category = window.choices.getValue(true);
    
        let realLocationHref = location.href.split('?')[0];

        if(name == '' && smallerPrice == '' && biggerPrice == "" && category)
            location.href = realLocationHref;
    
        let query = []; 
        if(name) query.push("name="+name);
        if(smallerPrice) query.push("smallerPrice="+smallerPrice);
        if(biggerPrice) query.push("biggerPrice="+biggerPrice);
        if(category.length > 0) query.push("category="+JSON.stringify(category));
    
        let getRequest = realLocationHref+"?"+query.join('&');
    
        location.href = getRequest;
    });
      
    document.querySelector('#filtrosCompradorDashboard form [name="smallerPrice"]').addEventListener('change', () => {
        let minPrice = document.querySelector('#filtrosCompradorDashboard form [name="smallerPrice"]').value;
        document.querySelector('#filtrosCompradorDashboard form [name="biggerPrice"]').setAttribute('min', minPrice);
    });

    document.querySelector('#filtrosCompradorDashboard form [name="biggerPrice"]').addEventListener('change', () => {
        let maxPrice = document.querySelector('#filtrosCompradorDashboard form [name="biggerPrice"]').value;
        document.querySelector('#filtrosCompradorDashboard form [name="smallerPrice"]').setAttribute('max', maxPrice);
    });
}