const { toInteger } = require('lodash');

require('./bootstrap');

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
  });

//APPEND CATEGORY

//TODO: REDO FUNCTION SO IT USES AJAX TO CALL FUNCTION WHICH RETURNS CATEGORY
function getCategoryName(id) {

    id = toInteger(id);

    switch(id) {
        case 1:
            return 'School';
        case 2:
            return 'Work';
        case 3:
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

/* -- COMMENT EDIT -- */

var commentMessage = [];
var commentIsEditing = [];

function editComment(comment, id) {

    commentIsEditing[id] = true;

    let body = comment.find('.comment-body');

    let message = body.text().trim();

    commentMessage[id] = message;

    body.empty();

    body.append('<div class="input-field col s12" style="float: none;"> <textarea name="comment" class="materialize-textarea">'+ message +'</textarea></div>');

    body.find('.materialize-textarea').select().focus();

    let footer = comment.find('.comment-footer');

    footer.prepend('<span class="save"> <button class="save-btn" data-comment="'+ id +'">Save</button> | </span>');

    footer.find('.edit-btn').text('Cancel');

}

function closeEditComment(comment, id) {

    commentIsEditing[id] = false;

    let body = comment.find('.comment-body');

    body.empty();

    body.append(commentMessage[id]);

    let footer = comment.find('.comment-footer');

    footer.find('.edit-btn').text('Edit');

    footer.find('.save').remove();

}

function saveComment(comment, id) {

    let body = comment.find('.comment-body');

    let message = body.find('.materialize-textarea').val();

    commentMessage[id] = message;

    let csrf = $('.comments input[name="_token"]').val();

    let task_id = $('.task').data('task');

    let url = BASE_URL + '/tasks/'+ task_id +'/comments/' + id;

    let data = {
        id: id,
        comment: message,
        _token: csrf,
        _method: 'PATCH'
    };

    $.ajax({
        type: 'PATCH',
        url: url,
        data: data,
        success: (data) => {
            
        },
        error: (data) => {
            
        }
    });

    closeEditComment(comment, id);

}

/* -- AJAX FORM -- */

function sendForm(form) {

    let methodInput = form.find('input[name="_method"]');

    let method = methodInput.length ? methodInput.val() : form.attr('method');

    var result = {
        success: false,
    };

    $.ajax({
        type: method,
        url: form.attr('action'),
        data: form.serialize(),
        async: false,
        success: (data) => {
            result.success = true;
            result.data = data;
        },
        error: (data) => {
            
        }
    });

    return result;

}

/* -- AJAX FILTERING -- */
function changeQueryString(param, value) {
    if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search)
        searchParams.set(param, value);
        var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
        history.pushState(null, '', newRelativePathQuery);
    }
}

function changeSorting() {

    sort_by = $('.filter-sort-by select[name="sort_by"]').val();
    order = $('.filter-sort-by input[name="order"]').val();

    changeQueryString("sort_by", sort_by);
    changeQueryString("order", order);

    let page_url = window.location.href;
    page_url = page_url.replace('&page=' + params.page, '');
    page_url = page_url.replace('?page=' + params.page + '&', '?');
    page_url = page_url.replace('&page=' + 1, '');
    page_url = page_url.replace('?page=' + 1 + '&', '?');

    let data = {
        task_name: params.task_name,
        visibility: params.visibility,
        status: params.status,
        category: params.category,
        membership: params.membership,
        shared_with: params.shared_with,
        from: params.from,
        to: params.to,
        sort_by: sort_by,
        order: order,
        isAjax: 1,
        page_url: page_url
    };

    const url = window.location.origin + window.location.pathname

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        async: true,
        success: (data) => {
            $('.result .list, .pagination').remove();
            $('.result').append(data);
        },
        error: (data) => {
            
        }
    });

    changeQueryString("page", 1);

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

    $('.category-' + id).remove();

    addCategoryToSelect(id);

    deleteCategoryBox(id);

});

$('#visibility input').on('change', (event) => {

    let element = event.target;

    let value = $(element).is(":checked");

    let message = getVisibilityMessage(value);

    $('#visibility h4 i').text(message);

});

$('.comment .edit-btn').on('click', (event) => {

    let element = $(event.target);

    let comment_id = element.data('comment');

    let comment = $('#comment-' + comment_id);

    if(commentIsEditing[comment_id]) {
        closeEditComment(comment, comment_id);
        return;
    }

    editComment(comment, comment_id);

});

$(document).on('click', '.comment .save-btn', (event) => {

    let element = $(event.target);

    let comment_id = element.data('comment');

    let comment = $('#comment-' + comment_id);

    saveComment(comment, comment_id);

});

$('button.order').on('click', (event) => {

    event.preventDefault();

    let element = $(event.target).parent();

    let input = $('input[name="order"]');

    if(input.val() == '1') {

        input.val(0);

        element.find('b').text(filterLang.descending);

        element.find('.material-icons').text('keyboard_arrow_down');

        changeSorting();

        return;
    }

    input.val(1);

    element.find('b').text(filterLang.ascending);

    element.find('.material-icons').text('keyboard_arrow_up');

    changeSorting();

});

$('.filter-sort-by select[name="sort_by"], .filter-sort-by input[name="order"]').on('change', (event) => {

    changeSorting();

});

$('.showcase-controls a').on('click', (event) => {

    let element = $(event.target);

    let page = element.data('item');

    $('.carousel').carousel('set', page);

});

// $('#members .add-member-form').on('submit', (event) => {

//     event.preventDefault();

//     let form = $(event.target);

//     let result = sendForm(form);

//     if(!result.success) {
//         $('#username').val('');
//         return;
//     }

//     let data = form.serializeArray().reduce(function(m,o){  m[o.name] = o.value; return m;}, {});

//     let task_id = $('.task').data('task');

//     let item = '<td class="item"><span>'+ data.username +'</span></td>';

//     let control = `<td class="control">
//         <form class="delete-member-form" action="`+ BASE_URL + '/tasks/' + task_id + '/members/' + result.data + `" method="POST">
//             <input type="hidden" name="_token" value="`+ data._token +`">
//             <input type="hidden" name="_method" value="DELETE">
//             <a class="modal-trigger" href="#member-delete-`+ result.data +`"> 
//                 <i class="material-icons">clear</i> 
//             </a>
//             <div id="member-delete-`+ result.data +`" class="modal">

//                 <div class="modal-content">

//                     <h5>`+ membersLang.deleteMemberConfirm(data.username) +`</h5>

//                     <div class="input-field">

//                         <button type="submit" class="waves-effect waves-red chip btn" name="member-remove" value="`+ result.data +`">
//                             `+ membersLang.deleteMember +`
//                         </button>

//                         <a class="waves-effect waves-light chip text-white btn modal-trigger teal lighten-2" onclick="$('#member-delete-`+ result.data +`').modal('close');">
//                             <span>`+ membersLang.cancel +`</span>
//                         </a>

//                     </div>

//                 </div>

//             </div>
//         </form>
//     </td>`;

//     $('#username').val('');

//     $('#members .member-box tbody').append('<tr class="member member-' + result.data + '">'+ item + control + '</tr>');

//     $('#members .modal').modal();

// });

// $(document).on('submit', '#members .delete-member-form', (event) => {

//     event.preventDefault();

//     let form = $(event.target);

//     let result = sendForm(form);

//     if(!result.success) {
//         return;
//     }

// });