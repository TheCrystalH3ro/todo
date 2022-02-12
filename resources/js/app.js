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
            let popupText = '<div class="popup-message"><i class="material-icons text-success">check</i><span>'+ commentsLang.commentUpdated +'</span></div>';
            M.toast({html: popupText, classes: 'rounded'});
        },
        error: (data) => { 
            let popupText = '<div class="popup-message"><i class="material-icons text-failure">check</i><span>'+ commentsLang.commentUpdateFail +'</span></div>';
            M.toast({html: popupText, classes: 'rounded'});
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

function getOriginalFilterData() {

    let shared_with_inputs = $('.original-inputs input[name="orig_shared_with[]"]');

    let shared_with = [];

    shared_with_inputs.each((id, element) => {
        shared_with.push($(element).val());
    });

    let category_inputs = $('.original-inputs input[name="orig_category[]"]');

    let category = [];

    category_inputs.each((id, element) => {
        category.push($(element).val());
    });

    let data = {
        task_name: $('.original-inputs input[name="orig_task_name"]').val(),
        visibility: $('.original-inputs input[name="orig_visibility"]').val(),
        status: $('.original-inputs input[name="orig_status"]').val(),
        category: category,
        membership: $('.original-inputs input[name="orig_membership"]').val(),
        shared_with: shared_with,
        from: $('.original-inputs input[name="orig_from"]').val(),
        to: $('.original-inputs input[name="orig_to"]').val(),
    };

    return data;

}

function getFilterData() {

    let shared_with_inputs = $('.filter-box input[name="shared_with[]"]');

    let shared_with = [];

    shared_with_inputs.each((id, element) => {
        shared_with.push($(element).val());
    });

    let data = {
        task_name: $('.filter-box #task-name').val(),
        visibility: $('.filter-box select[name="visibility"]').val(),
        status: $('.filter-box select[name="status"]').val(),
        category: $('.filter-box select[name="category[]"]').val(),
        membership: $('.filter-box select[name="membership"]').val(),
        shared_with: shared_with,
        from: $('.filter-box .datepicker[name="from"]').val(),
        to: $('.filter-box .datepicker[name="to"]').val(),
        sort_by: $('.filter-sort-by select[name="sort_by"]').val(),
        order: $('.filter-sort-by input[name="order"]').val(),
    };

    return data;

}

function changeQueryString(param, value) {
    if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search)
        searchParams.set(param, value);
        var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
        history.pushState(null, '', newRelativePathQuery);
    }
}

function clearQueryString() {
    history.pushState(null, '', window.location.pathname);
}

function appendToQueryString(param, value) {
    if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search)
        searchParams.append(param, value);
        var newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
        history.pushState(null, '', newRelativePathQuery);
    }
}

function updateParamData(data) {

    changeQueryString("task_name", data.task_name);
    changeQueryString("visibility", data.visibility);
    changeQueryString("status", data.status);
    changeQueryString("membership", data.membership);
    changeQueryString("from", data.from);
    changeQueryString("to", data.to);

    data.category.forEach((value, key) => {

        if(!key) {
            changeQueryString('category[]', value);
            return;
        }

        appendToQueryString('category[]', value);
    });

    data.shared_with.forEach((value, key) => {

        if(!key) {
            changeQueryString('shared_with[]', value);
            return;
        }

        appendToQueryString('shared_with[]', value);
    });

}

function changeSorting() {

    $('.result .overlay').css('display', 'flex');

    sort_by = $('.filter-sort-by select[name="sort_by"]').val();
    order = $('.filter-sort-by input[name="order"]').val();

    changeQueryString("sort_by", sort_by);
    changeQueryString("order", order);

    let page_url = window.location.href;
    page_url = page_url.replace('&page=' + params.page, '');
    page_url = page_url.replace('?page=' + params.page + '&', '?');
    page_url = page_url.replace('&page=' + 1, '');
    page_url = page_url.replace('?page=' + 1 + '&', '?');

    let data = getOriginalFilterData();

    data.isAjax = 1;
    data.page_url = page_url;
    data.sort_by = sort_by;
    data.order = order;

    const url = window.location.origin + window.location.pathname

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        async: true,
        success: (data) => {
            $('.result .list, .pagination, .original-inputs').remove();
            $('.result .list-wrapper').append(data);
            $('.result .overlay').css('display', 'none');
        },
        error: (data) => {
            
        }
    });

    changeQueryString("page", 1);

}

function changeListPage(page) {

    $('.result .overlay').css('display', 'flex');

    sort_by = $('.filter-sort-by select[name="sort_by"]').val();
    order = $('.filter-sort-by input[name="order"]').val();

    changeQueryString("page", page);

    let page_url = window.location.href;

    let data = getOriginalFilterData();

    data.isAjax = 1;
    data.page = page;
    data.page_url = page_url;
    data.sort_by = sort_by;
    data.order = order;

    const url = window.location.origin + window.location.pathname

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        async: true,
        success: (data) => {
            $('.result .list, .pagination, .original-inputs').remove();
            $('.result .list-wrapper').append(data);
            $('.result .overlay').css('display', 'none');
        },
        error: (data) => {
            
        }
    });

}

function changeListFiltering() {

    $('.result .overlay').css('display', 'flex');

    let page_url = window.location.href;
    page_url = page_url.replace('&page=' + params.page, '');
    page_url = page_url.replace('?page=' + params.page + '&', '?');
    page_url = page_url.replace('&page=' + 1, '');
    page_url = page_url.replace('?page=' + 1 + '&', '?');

    let data = getFilterData();

    data.isAjax = 1;
    data.page = 1;
    data.page_url = page_url;

    updateParamData(data);

    const url = window.location.origin + window.location.pathname;

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        async: true,
        success: (data) => {
            $('.result .list, .pagination, .original-inputs').remove();
            $('.result .list-wrapper').append(data);
            $('.result .overlay').css('display', 'none');
        },
        error: (data) => {
            
        }
    });

}

function resetFilters() {

    $('.result .overlay').css('display', 'flex');

    $('.filter-box select, .filter-box input').val('');
    $('.filter-box select').formSelect();
    $('.filter-box input + label').removeClass('active');

    $('.filter-box .chips.input-field .chip').remove();
    $('.filter-box .shared-inputs').empty();

    let page_url = window.location.href;
    page_url = page_url.replace('&page=' + params.page, '');
    page_url = page_url.replace('?page=' + params.page + '&', '?');
    page_url = page_url.replace('&page=' + 1, '');
    page_url = page_url.replace('?page=' + 1 + '&', '?');

    let data = {
        isAjax: 1,
        page_url: page_url
    };

    clearQueryString();

    const url = window.location.origin + window.location.pathname;

    $.ajax({
        type: 'GET',
        url: url,
        data: data,
        async: true,
        success: (data) => {
            $('.result .list, .pagination, .original-inputs').remove();
            $('.result .list-wrapper').append(data);
            $('.result .overlay').css('display', 'none');
        },
        error: (data) => {
            
        }
    });

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

// PAGE BUTTON
$(document).on('click', '.pagination .waves-effect a', (event) => {

    event.preventDefault();

    let element = $(event.target);

    let page = element.data('page');

    changeListPage(page);
    
});

//FILTER BUTTON
$('.filter-box .filter-buttons button[type="submit"]').on('click', (event) => {
    
    event.preventDefault();
    
    changeListFiltering();
    
});

//RESET FILTER BUTTON
$('.filter-box .filter-buttons a').on('click', (event) => {
    
    event.preventDefault();
    
    resetFilters();
    
});

$('.showcase-controls a').on('click', (event) => {

    let element = $(event.target);

    let page = element.data('item');

    $('.carousel').carousel('set', page);

});