document.addEventListener("DOMContentLoaded", function(){
    var cb = document.querySelectorAll('#table-params input[type="checkbox"]');

    //проходим по каждому чекбоксу и вешаем функцию по активации
    cb.forEach( function(cbk) {
        cbk.addEventListener("change", function(e){
    		e.target.parentNode.nextElementSibling.readOnly = e.target.checked;
        });
    });

});
