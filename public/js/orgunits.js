document.addEventListener("DOMContentLoaded", ready);
var btn_add = document.getElementById('btn-add');
var btn_delete = document.getElementById('btn-delete');
var btn_edit = document.getElementById('btn-edit');
var btn_show = document.getElementById('btn-show');

var id_field = document.getElementById('org_unit_id');


function ready() {
    let orgUnits = document.querySelectorAll(".single-unit strong")

    let icons = document.getElementsByClassName("can-expand");

    let i = icons.length;
    while(i--)
    {
        icons[i].addEventListener("click", iconExpandClick);
    }

    i = orgUnits.length;
    while(i--)
    {
        orgUnits[i].addEventListener("click", selectText);
    }

    if(btn_add != null)
        btn_add.addEventListener("click", btnAddClick);
    if(btn_delete != null)
        btn_delete.addEventListener("click", btnDeleteClick);
    if(btn_edit != null)
        btn_edit.addEventListener("click", btnEditClick);
    btn_show.addEventListener("click", btnShowClick);
}

function selectText(e)
{
    if(btn_show.classList.contains('disabled'))
    {
        if(btn_delete)
            btn_delete.classList.remove('disabled');
        if(btn_add)
            btn_add.classList.remove('disabled');
        if(btn_edit)
            btn_edit.classList.remove('disabled');
        btn_show.classList.remove('disabled');
    }
    let selected = document.querySelector('.active-unit');
    if(selected != null)
        selected.classList.remove('active-unit');

    e.target.classList.add('active-unit');

    if(id_field)
        id_field.value = e.target.dataset.value;
}

function iconExpandClick(e)
{
    let liItem = e.target.parentNode.querySelector('li');
    let childUl = e.target.parentNode.querySelector('li ul');
    if(childUl == null)
        return;

    if(liItem.classList.contains('collapsed-orgunit'))
    {
        liItem.classList.remove('collapsed-orgunit');
        liItem.classList.add('expanded-orgunit');

        e.target.classList.remove('fa-search-plus');
        e.target.classList.add('fa-search-minus');

        childUl.style.display = "block";
    }

    else if(liItem.classList.contains('expanded-orgunit'))
    {
        liItem.classList.remove('expanded-orgunit');
        liItem.classList.add('collapsed-orgunit');

        e.target.classList.remove('fa-search-minus');
        e.target.classList.add('fa-search-plus');

        childUl.style.display = "none";
    }
}

//добавление узла с указанием родителя
function btnAddClick(e)
{
    if(e.target.classList.contains('disabled'))
        return;
    e.preventDefault();

    let url = e.target.dataset.url;
    let parent = document.querySelector('.active-unit').dataset.value;

    document.location.href = url + '/' + parent;
}

//редактирование узла с указанием узла
function btnEditClick(e)
{
    if(e.target.classList.contains('disabled'))
        return;
    e.preventDefault();

    let url = e.target.dataset.url;
    let value = document.querySelector('.active-unit').dataset.value;

    document.location.href = url + '/' + value + '/edit';
}

//подробности узла с указанием узла
function btnShowClick(e)
{
    if(e.target.classList.contains('disabled'))
        return;
    e.preventDefault();

    let url = e.target.dataset.url;
    let parent = document.querySelector('.active-unit').dataset.value;

    document.location.href = url + '/' + parent;

}

//удаление узла через отправку формы
function btnDeleteClick(e)
{
    if(e.target.classList.contains('disabled'))
        return;
    e.preventDefault();


    form = document.getElementById('formDelete');
    if(form == null)
        console.log('Отсутствует форма удаления');
    else
    {
        form.submit();
        console.log("Успешно обработано");
    }
}
