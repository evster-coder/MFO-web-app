$(document).ready(function(){

  function clearIconData()
  {
    $('#icon-loanNumber').html('');
    $('#icon-loanConclusionDate').html('');
  }


  var clientformNumber = "";
  var loanConclusionDate = "";
  var clientFIO = "";
  var statusOpen = "";
  var page = $('#hiddenPage').val();
  var sortColumn = $('#hiddenSortColumn').val();
  var sortDesc = $('#hiddenSortDesc').val();


  function loadData()
  {    
    loanNumber = $('#searchLoanNumber').val();
    clientFIO = $('#searchClientFIO').val();
    loanConclusionDate = $('#searchLoanConclusionDate').val();
    statusOpen = $('#searchStatusOpen').val();
    sortColumn = $('#hiddenSortColumn').val();
    sortDesc = $('#hiddenSortDesc').val();


    $.ajax({
     url:"/loans/get-loans?page="+ page +
          "&sortby=" + sortColumn +
          "&sortdesc=" + sortDesc +
          "&loanNumber=" + loanNumber +
          "&clientFIO" + clientFIO +
          "&loanConclusionDate" + loanConclusionDate + 
          "&statusOpen" + statusOpen,

     success:function(data)
       {
        //очищаем tbody и заполняем снова
        $('tbody').html('');
        $('tbody').html(data);
       }
    })
  }

 $(document).on('keyup', '#searchLoanNumber', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }
 });

 $(document).on('keyup', '#searchLoanConclusionDate', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }
 }); 

 $(document).on('keyup', '#searchClientFIO', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }
 }); 

 $(document).on('change', '#searchStatusOpen', function(e){
    if(e.key=="Enter")
    {
      //получение данных
      loadData();
    }
 });


 $(document).on('click', '.sorting', function(){
    var sortColumn = $(this).data('column-name');
    var orderDesc = $(this).data('sorting-type');

    var newOrder = '';
    if(orderDesc == 'asc')
    {
       $(this).data('sorting-type', 'desc');
       newOrder = 'desc';
       clearIconData();

      $('#icon-'+ sortColumn).html('<i class="fas fa-angle-up"></i>');
    }
    if(orderDesc == 'desc')
    {
       $(this).data('sorting-type', 'asc');
       newOrder = 'asc';
       clearIconData();

      $('#icon-' + sortColumn).html('<i class="fas fa-angle-down"></i>');
    }

    $('#hiddenSortColumn').val(sortColumn);
    $('#hiddenSortDesc').val(newOrder);

    loadData();
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