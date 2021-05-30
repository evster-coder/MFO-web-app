$(document).ready(function(){

  var clientformNumber = "";
  var loanDate = "";
  var clientFIO = "";
  var state = "";
  var page = $('#hiddenPage').val();


  function loadData()
  {
    clientformNumber = $('#searchClientFormNumber').val();
    clientFIO = $('#searchClientFIO').val();
    loanDate = $('#searchClientFormDate').val();
    state = $('#searchState').val();

    $.ajax({
     url:"/clientforms/get-clientforms?page="+ page +
          "&id=" + clientformNumber +
          "&loanDate=" + loanDate +
          "&clientFio=" + clientFIO + 
          "&state=" + state,
       success:function(data)
       {
        //очищаем tbody и заполняем снова
        $('tbody').html('');
        $('tbody').html(data);
       }
    })
  }


  $(document).on('keyup', '#searchClientFormNumber', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }

  });


  $(document).on('keyup', '#searchClientFormDate', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }
  });

  $(document).on('change', '#searchState', function(e){
      //получение данных
      loadData();
  });



  $(document).on('keyup', '#searchClientFIO', function(e){
    if(e.key=="Enter")
    {
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

});