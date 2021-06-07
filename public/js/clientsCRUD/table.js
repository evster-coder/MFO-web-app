$(document).ready(function(){

  var name = "";
  var surname = "";
  var patronymic = "";
  var birthDate = "";
  var page = $('#hiddenPage').val();


  function loadData()
  {
    $.ajax({
     url:"/clients/get-clients?page="+ page +
          "&surname=" + surname +
          "&name=" + name +
          "&patronymic=" + patronymic +
          "&birth-date=" + birthDate,
     success:function(data)
       {
        //очищаем tbody и заполняем снова
        $('tbody').html('');
        $('tbody').html(data);
       }
    })
  }


  $(document).on('keyup', '#searchSurname', function(e){
    if(e.key=="Enter")
    {
      //параметры поиска
      surname = $('#searchSurname').val();

      //получение данных
      loadData();
    }

  });


  $(document).on('keyup', '#searchName', function(e){
    if(e.key=="Enter")
    {
      //параметры поиска
      name = $('#searchName').val();

      //получение данных
      loadData();
    }
  });


  $(document).on('keyup', '#searchPatronymic', function(e){
    if(e.key=="Enter")
    {
      //параметры поиска
      patronymic = $('#searchPatronymic').val();

      //получение данных
      loadData();
    }
  });


  $(document).on('keyup', '#searchBirthDate', function(e){
    if(e.key=="Enter")
    {
      //параметры поиска
      birthDate = $('#searchBirthDate').val();

      //получение данных
      loadData();
    }
  });


  $(document).on('click', '.pagination a', function(e){
    e.preventDefault();

    //новое значение страницы
    page = $(this).attr('href').split('page=')[1];
    $('#hiddenPage').val(page);

    $('li').removeClass('active');
          $(this).parent().addClass('active');

    loadData();
  });

    $(document).on('click', '#exportExcel', function(event){
    event.preventDefault();

    var url = $(this).data('export');
    surname = $('#searchSurname').val();
    name = $('#searchName').val();
    patronymic = $('#searchPatronymic').val();
    birthDate = $('#searchBirthDate').val();
    
    window.location.href = url +           
          "?surname=" + surname +
          "&name=" + name +
          "&patronymic=" + patronymic +
          "&birth-date=" + birthDate;

  });
});