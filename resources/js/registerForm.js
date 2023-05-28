import axios from 'axios';

window.onload = function() {
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

    document.querySelector("#state").addEventListener('change', async function(){

        let state = document.querySelector("#state").value;

        let axiosConfig = {
            headers: {
                "Access-Control-Allow-Origin": "*",
                'Content-Type': 'application/json;charset=UTF-8'
            }
        };
        
        await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+state+'/municipios', axiosConfig)
            .then(response => {

                let optionList = document.querySelector('#city').options;

                if(optionList.length != 1){
                    let aux = (optionList.length) - 1;
                    while (optionList.length != 0) {
                        optionList.remove(optionList[aux--]);
                    }
    
                    optionList.add(new Option("Selecione uma cidade", "", true));
                    optionList[0].setAttribute('disabled', true);
                }

                response.data.forEach(option =>
                    optionList.add(
                        new Option(option.nome, option.nome)
                    )
                );
            })
            .catch(error => console.log(error.response.data));
    });
};