import './bootstrap';

import Choices from "choices.js";

import './editProduto';

window.onload = function() {
    if(location.href.indexOf('register') != -1){
        document.querySelectorAll('form input[name="typeUser"]').forEach(function(elem){
            elem.addEventListener('change', function(){
                if(elem.value == "comprador"){
                    document.querySelector('#compradorForm').classList.remove('d-none');
                    requireAllInputs(true);
                }
                else{
                    document.querySelector('#compradorForm').classList.add('d-none');
                    requireAllInputs(false);
                }
            });
        });
    
        function requireAllInputs($confirm) {
            document.querySelectorAll('#compradorForm input, #compradorForm select').forEach(function(elem){
                if($confirm) elem.setAttribute('required', true);
                else elem.removeAttribute('required');
            });
        }
    }

    var routes = [
        'minhas-compras',
        'dashboard'
    ];
    
    let result = routes.filter(function(currentValue){
        return location.href.indexOf(currentValue) != -1;
    });

    if(result.length > 0){
        var choices = new Choices('#filtrosProdutos #category', {
            placeholderValue: "Selecione uma categoria"
        });
    
        window.choices = choices;

        document.querySelector('#filtrosProdutos form').addEventListener('submit', (e) => {
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
          
        document.querySelector('#filtrosProdutos form [name="smallerPrice"]').addEventListener('change', () => {
            let minPrice = document.querySelector('#filtrosProdutos form [name="smallerPrice"]').value;
            document.querySelector('#filtrosProdutos form [name="biggerPrice"]').setAttribute('min', minPrice);
        });
    
        document.querySelector('#filtrosProdutos form [name="biggerPrice"]').addEventListener('change', () => {
            let maxPrice = document.querySelector('#filtrosProdutos form [name="biggerPrice"]').value;
            document.querySelector('#filtrosProdutos form [name="smallerPrice"]').setAttribute('max', maxPrice);
        });
    }

    if(location.href.indexOf('admin/compradores')!= -1){
        var choicesStates = new Choices('#filtrosCompradorFromAdmin #states', {
            placeholderValue: "Selecione um estado"
        });
    
        window.choicesStates = choicesStates;

        document.querySelector('#filtrosCompradorFromAdmin form').addEventListener('submit', (e) => {
            e.preventDefault();
    
            let form = document.querySelector("#collapseExample");
        
            let name = form.querySelector('[name="name"]').value;
            let minCredit = form.querySelector('[name="minCredit"]').value;
            let maxCredit = form.querySelector('[name="maxCredit"]').value;
            let states = window.choicesStates.getValue(true);
            let cpf = form.querySelector('[name="cpf"]').value;
            let minDate = form.querySelector('[name="minDate"]').value;
            let maxDate = form.querySelector('[name="maxDate"]').value;
        
            let realLocationHref = location.href.split('?')[0];
    
            if(name == '' && minCredit == '' && maxCredit == "" && states
                && cpf && minDate && maxDate)
            {
                location.href = realLocationHref;
            }
        
            let query = []; 
            if(name) query.push("name="+name);
            if(minCredit) query.push("minCredit="+minCredit);
            if(maxCredit) query.push("maxCredit="+maxCredit);
            if(states.length > 0) query.push("states="+JSON.stringify(states));
            if(minDate) query.push("minDate="+minDate);
            if(maxDate) query.push("maxDate="+maxDate);
            if(cpf) query.push("cpf="+cpf);
        
            let getRequest = realLocationHref+"?"+query.join('&');
        
            location.href = getRequest;
        });
          
        document.querySelector('#filtrosCompradorFromAdmin [name="minCredit"]').addEventListener('change', () => {
            let minCredit = document.querySelector('#filtrosCompradorFromAdmin [name="minCredit"]').value;
            document.querySelector('#filtrosCompradorFromAdmin [name="maxCredit"]').setAttribute('min', minCredit);
        });
    
        document.querySelector('#filtrosCompradorFromAdmin [name="maxCredit"]').addEventListener('change', () => {
            let maxCredit = document.querySelector('#filtrosCompradorFromAdmin [name="maxCredit"]').value;
            document.querySelector('#filtrosCompradorFromAdmin [name="minCredit"]').setAttribute('max', maxCredit);
        });

        document.querySelector('#filtrosCompradorFromAdmin [name="minDate"]').addEventListener('change', () => {
            let minDate = document.querySelector('#filtrosCompradorFromAdmin [name="minDate"]').value;
            document.querySelector('#filtrosCompradorFromAdmin [name="maxDate"]').setAttribute('min', minDate);
        });
    
        document.querySelector('#filtrosCompradorFromAdmin [name="maxDate"]').addEventListener('change', () => {
            let maxDate = document.querySelector('#filtrosCompradorFromAdmin [name="maxDate"]').value;
            document.querySelector('#filtrosCompradorFromAdmin [name="minDate"]').setAttribute('max', maxDate);
        });
    }
    else if(location.href.indexOf('admin/vendedores') != -1){

        document.querySelector('#filtrosVendedorFromAdmin form').addEventListener('submit', (e) => {
            e.preventDefault();
    
            let form = document.querySelector("#collapseExample");
        
            let name = form.querySelector('[name="name"]').value;
            let minCredit = form.querySelector('[name="minCredit"]').value;
            let maxCredit = form.querySelector('[name="maxCredit"]').value;
            let status = form.querySelector('[name="status"]').value;
        
            let realLocationHref = location.href.split('?')[0];
    
            if(name == '' && minCredit == '' && maxCredit == "" && status == "")
                location.href = realLocationHref;
        
            let query = []; 
            if(name) query.push("name="+name);
            if(minCredit) query.push("minCredit="+minCredit);
            if(maxCredit) query.push("maxCredit="+maxCredit);
            if(status) query.push("status="+status);
        
            let getRequest = realLocationHref+"?"+query.join('&');
        
            location.href = getRequest;
        });
          
        document.querySelector('#filtrosVendedorFromAdmin [name="minCredit"]').addEventListener('change', () => {
            let minCredit = document.querySelector('#filtrosVendedorFromAdmin [name="minCredit"]').value;
            document.querySelector('#filtrosVendedorFromAdmin [name="maxCredit"]').setAttribute('min', minCredit);
        });
    
        document.querySelector('#filtrosVendedorFromAdmin [name="maxCredit"]').addEventListener('change', () => {
            let maxCredit = document.querySelector('#filtrosVendedorFromAdmin [name="maxCredit"]').value;
            document.querySelector('#filtrosVendedorFromAdmin [name="minCredit"]').setAttribute('max', maxCredit);
        });
    }
    else if(location.href.indexOf('admin/produtos') != -1){
        var choicesCategory = new Choices('#filtrosProdutosFromAdmin #category', {
            placeholderValue: "Selecione uma categoria"
        });
    
        window.choicesCategory = choicesCategory;

        document.querySelector('#filtrosProdutosFromAdmin form').addEventListener('submit', (e) => {
            e.preventDefault();
    
            let form = document.querySelector("#collapseExample");
        
            let name = form.querySelector('[name="name"]').value;
            let maxPrice = form.querySelector('[name="maxPrice"]').value;
            let minPrice = form.querySelector('[name="minPrice"]').value;
            let categories = window.choicesCategory.getValue(true);
        
            let realLocationHref = location.href.split('?')[0];
    
            if(name == '' && maxPrice == '' && minPrice == "" && categories)
                location.href = realLocationHref;
        
            let query = []; 
            if(name) query.push("name="+name);
            if(maxPrice) query.push("maxPrice="+maxPrice);
            if(minPrice) query.push("minPrice="+minPrice);
            if(categories.length > 0) query.push("categories="+JSON.stringify(categories));
        
            let getRequest = realLocationHref+"?"+query.join('&');
        
            location.href = getRequest;
        });
          
        document.querySelector('#filtrosProdutosFromAdmin [name="maxPrice"]').addEventListener('change', () => {
            let maxPrice = document.querySelector('#filtrosProdutosFromAdmin [name="maxPrice"]').value;
            document.querySelector('#filtrosProdutosFromAdmin [name="minPrice"]').setAttribute('max', maxPrice);
        });
    
        document.querySelector('#filtrosProdutosFromAdmin [name="minPrice"]').addEventListener('change', () => {
            let minPrice = document.querySelector('#filtrosProdutosFromAdmin [name="minPrice"]').value;
            document.querySelector('#filtrosProdutosFromAdmin [name="maxPrice"]').setAttribute('min', minPrice);
        });
    }
};