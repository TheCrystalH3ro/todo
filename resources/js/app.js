const { toInteger } = require('lodash');

require('./bootstrap');

//APPEND CATEGORY

//TODO: REDO FUNCTION SO IT USES AJAX TO CALL FUNCTION WHICH RETURNS CATEGORY
function getCategoryName(id) {

    id = toInteger(id);

    switch(id) {
        case 0:
            return 'School';
        case 1:
            return 'Work';
        case 2:
            return 'Self-care';
        default:
            return 'Category';
    }

}

//TODO: EDIT TO MAKE AN AJAX CALL TO METHOD WHICH RETURNS ELEMENT INSTEAD
function getCategoryElement(id) {

    let name = getCategoryName(id);

    return `
        <tr class="category category-`+ id +`">
            <td class="item">
                <span>` + name + `</span>
                <input type="hidden" name="add-category[]" value="`+ id +`">
            </td>
            <td class="control">
                <a href="!#" class="delete-category" data-category="` + id + `"> <i class="material-icons">clear</i> </a>
            </td>
        </tr>
    `;

}

function getCategoryBox(id) {

    let name = getCategoryName(id);

    return `
        <li class="category-item category-item-`+ id +`">
            <span class="chip"> `+ name +` </span>
        </li>
    `;

}

function refreshCategorySelect() {

    element = $('#category-select');

    let select = M.FormSelect.getInstance(element);

    select.destroy();

    element.value = "";

    $(element).formSelect();

}

function getCategorySelect(id) {

    let name = getCategoryName(id);

    return `<option class="category-select-`+ id +`" value="`+ id + `">` + name +`</option>`;

}

function getDeleteInput(id) {
    return `<input type="hidden" name="delete-category[]" value="`+ id +`"></input>`;
}

function getPrevSelectElement(id) {

    let element;
    
    //LOOK FOR PREVIOUS ID
    do {
        
        id--;

        if(id < 0) {
            element = $('#category-select option').first();
            break;
        }

        element = $('.category-select-' + id).last();

    } while (!element.length);

    return element;

}

function addCategoryToSelect(id) {

    let prev = getPrevSelectElement(id);

    let select = getCategorySelect(id);

    $(select).insertAfter(prev);

    refreshCategorySelect();

}

function addCategoryToList(id) {

    let element = getCategoryElement(id);

    $('.category-box table tbody').append(element);
}

function addCategoryBox(id) {

    let element = getCategoryBox(id);

    $('.category-list').append(element);

}

function deleteCategoryBox(id) {

    $('.category-item-' + id).remove();

}

/* -- EVENTS --  */

//SELECT CATEGORY FROM DRODPOWN
$('#categories .input-field select').on('change', (event) => {

    event.preventDefault();

    let element = event.target;

    let value = element.value;

    let select = M.FormSelect.getInstance(element);
    
    addCategoryToList(value);

    select.destroy();

    $('.category-select-' + value).remove();

    element.value = "";

    $(element).formSelect();

    addCategoryBox(value);

});

//DESELECT CATEGORY BUTTON
$(document).on('click', '.delete-category', (event) => {

    event.preventDefault();

    let element = $(event.target).parent();

    let id = element.data('category');

    //CREATE DELETE INPUT
    if(!$('.category-' + id + ' input').length) {
        $('.delete-categories').append(getDeleteInput(id));
    }

    $('.category-' + id).remove();

    addCategoryToSelect(id);

    deleteCategoryBox(id);

});