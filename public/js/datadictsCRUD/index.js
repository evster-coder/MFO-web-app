$(document).ready(function () {
	var fieldName = $('#form-data').prop('name');
	var ajaxUrl = $('#data-table').data('url');

	//новый объект
	$('#new-data').click(function () {
		//изменяем текст
		$('#dataCrudModal').html("Создать новый");
		$('#btn-save').html("Создать");

		//сбрасываем поля
		$('#dataForm').trigger("reset");

		//показываем окно
		$('#crud-modal').modal('show');
	}); 

	//редактирование объекта
	$('body').on('click', '#edit-data', function () {
		//получаем id из кнопки редактирования
		var dataId = $(this).data('id');
		var url = $(this).data('url');

		//получаем данные этого объекта
		$.get(url + dataId + '/edit', function (data) {

			//изменяем текст
			$('#dataCrudModal').html("Редактировать существующий");
			$('#btn-save').prop('disabled',false);
			$('#btn-save').html('Обновить');

			//показываем окно
			$('#crud-modal').modal('show');

			//запись значений в поля
			$('#dataId').val(data['id']);
			$('#form-data').val(data[fieldName]);
		});
	});

 	function loadData(page, query)
 	{
    	$.ajax({
     		url: ajaxUrl + "?page="+ page +
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

		    //текущая страница
		    var page = $('#hiddenPage').val();

		    //получение данных
		    loadData(page, query);
		}
 	});

 	$(document).on('click', '.pagination a', function(event){
	    event.preventDefault();

	    //новое значение страницы
	    var page = $(this).attr('href').split('page=')[1];
	    $('#hiddenPage').val(page);

	    var query = $('#search').val();

	    $('li').removeClass('active');
	          $(this).parent().addClass('active');

	    loadData(page, query);
	 });

});