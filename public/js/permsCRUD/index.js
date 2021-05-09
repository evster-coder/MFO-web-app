$(document).ready(function () {

	//новое право
	$('#new-perm').click(function () {
		//изменяем текст
		$('#permCrudModal').html("Добавить новое право");
		$('#btn-save').html("Создать");

		//сбрасываем поля
		$('#permForm').trigger("reset");

		//показываем окно
		$('#crud-modal').modal('show');
	});

	//редактирование права
	$('body').on('click', '#edit-perm', function () {
		//получаем id из кнопки редактирования
		var permId = $(this).data('id');

		//получаем данные этого права
		$.get('perm/edit/' + permId, function (data) {

			//изменяем текст
			$('#permCrudModal').html("Редактировать право");
			$('#btn-save').prop('disabled',false);
			$('#btn-save').html('Обновить');

			//показываем окно
			$('#crud-modal').modal('show');

			//запись значений в поля
			$('#permId').val(data.id);
			$('#name').val(data.name);
			$('#slug').val(data.slug);
		});
	});


  	function clearIconData()
  	{
    	$('#icon-name').html('');
    	$('#icon-slug').html('');
  	}

 	function loadData(page, sortDesc, sortColumn, query)
 	{
    	$.ajax({
     		url:"/perms/get-perms?page="+ page +
          	"&sortby=" + sortColumn +
          	"&sortdesc=" + sortDesc +
          	"&query=" + query,
     	success:function(data)
       	{
        //очищаем tbody и заполняем снова
        	$('tbody').html('');
        	$('tbody').html(data);
       	}
    	});
 	}


 	$(document).on('keyup', '#search', function(e){
 		if(e.key=="Enter")
 		{
		    //параметры поиска
		    var query = $('#search').val();

		    //название колонки и направление сортировки
		    var sortColumn = $('#hiddenSortColumn').val();
		    var sortDesc = $('#hiddenSortDesc').val();

		    //текущая страница
		    var page = $('#hiddenPage').val();

		    //получение данных
		    loadData(page, sortDesc, sortColumn, query);
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
	        $('#icon-'+sortColumn).html('<i class="fas fa-angle-up"></i>');
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

	    var page = $('#hiddenPage').val();
	    var query = $('#search').val();

	    loadData(page, newOrder, sortColumn, query);
	 });

 	$(document).on('click', '.pagination a', function(event){
	    event.preventDefault();

	    //новое значение страницы
	    var page = $(this).attr('href').split('page=')[1];
	    $('#hiddenPage').val(page);

	    var sortColumn = $('#hiddenSortColumn').val();
	    var sortDesc = $('#hiddenSortDesc').val();

	    var query = $('#search').val();

	    $('li').removeClass('active');
	          $(this).parent().addClass('active');

	    loadData(page, sortDesc, sortColumn, query);
	 });

});

  function validate()
  {
    if(document.permForm.name.value !='' && document.permForm.slug.value !='')
    {
      document.permForm.btnsave.disabled=false
    }
    else
      document.permForm.btnsave.disabled=true
  }