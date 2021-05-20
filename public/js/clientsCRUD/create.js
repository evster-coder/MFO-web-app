$(document).ready(function(){

  var surnameField = $('#surname');
  var nameField = $('#name');
  var patronymicField = $('#patronymic');
  var birthDateField = $('#birthDate');

  var btnAdd = $('#btnAdd');
  var createData = $('#createData');

 function loadData() {
    $.ajax({
      type: 'POST',
      url: btnAdd.data('url'),
      headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
      data: { surname: surnameField.val(),
              name: nameField.val(),
              patronymic: patronymicField.val(),
              birthDate: birthDateField.val()
            },
     success:function(data)
       {
        if(data)
          if(btnAdd.data('update') == '1')
          {          
            $('#sameClients').html('');
            $('#sameClients').html(data);
            btnAdd.data('update', 0);
            btnAdd.text('Добавить все равно');
          }
          else
          {
            $('#formCreate')[0].submit();
          }
        else
          $('#formCreate')[0].submit();
       }
    })
 }

 $(document).on('click', '#btnAdd', function(e){
      e.preventDefault();
      if($('#formCreate')[0].checkValidity())
        loadData();
      else
        $("#formCreate")[0].reportValidity();
 });

  $(document).on('input', '#surname', valuechanged);
  $(document).on('input', '#name', valuechanged);
  $(document).on('input', '#patronymic', valuechanged);
  $(document).on('input', '#birthDate', valuechanged);

  function valuechanged(e)
  {
    btnAdd.data('update', '1');
    btnAdd.text('Добавить');
  }
});