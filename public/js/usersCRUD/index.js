$(document).ready(function () {

    function clearIconData() {
        $('#icon-username').html('');
        $('#icon-fio').html('');
        $('#icon-orgunit').html('');
        $('#icon-blocked').html('');
    }

    function loadData(page, sortDesc, sortColumn, query) {
        $.ajax({
            url: "/users/get-users?page=" + page +
                "&sortby=" + sortColumn +
                "&sortdesc=" + sortDesc +
                "&query=" + query,
            success: function (data) {
                //очищаем tbody и заполняем снова
                $('tbody').html('');
                $('tbody').html(data);
            }
        })
    }

    $(document).on('keyup', '#search', function (e) {
        if (e.key == "Enter") {
            //параметры поиска
            let query = $('#search').val();

            //название колонки и направление сортировки
            let sortColumn = $('#hiddenSortColumn').val();
            let sortDesc = $('#hiddenSortDesc').val();

            //текущая страница
            let page = $('#hiddenPage').val();

            //получение данных
            loadData(page, sortDesc, sortColumn, query);
        }
    });

    $(document).on('click', '.sorting', function () {
        let sortColumn = $(this).data('column-name');
        let orderDesc = $(this).data('sorting-type');

        let newOrder = '';
        if (orderDesc == 'asc') {
            $(this).data('sorting-type', 'desc');
            newOrder = 'desc';
            clearIconData();

            if (sortColumn == "org_units.org_unit_code")
                $('#icon-orgunit').html('<i class="fas fa-angle-up"></i>');
            else
                $('#icon-' + sortColumn).html('<i class="fas fa-angle-up"></i>');
        }
        if (orderDesc == 'desc') {
            $(this).data('sorting-type', 'asc');
            newOrder = 'asc';
            clearIconData();

            if (sortColumn == "org_units.org_unit_code")
                $('#icon-orgunit').html('<i class="fas fa-angle-down"></i>');
            else
                $('#icon-' + sortColumn).html('<i class="fas fa-angle-down"></i>');
        }

        $('#hiddenSortColumn').val(sortColumn);
        $('#hiddenSortDesc').val(newOrder);

        let page = $('#hiddenPage').val();
        let query = $('#search').val();

        loadData(page, newOrder, sortColumn, query);
    });

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        //новое значение страницы
        let page = $(this).attr('href').split('page=')[1];
        $('#hiddenPage').val(page);

        let sortColumn = $('#hiddenSortColumn').val();
        let sortDesc = $('#hiddenSortDesc').val();

        let query = $('#search').val();

        $('li').removeClass('active');
        $(this).parent().addClass('active');

        loadData(page, sortDesc, sortColumn, query);
    });

    $(document).on('click', '#exportExcel', function (event) {
        event.preventDefault();

        let url = $(this).data('export');
        let query = $('#search').val();

        window.location.href = url + "?query=" + query;
    });
});
