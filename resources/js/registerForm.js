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
};