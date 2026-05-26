(function ($) {
    'use strict';

    var POLL_INTERVAL = 10000;
    var _pollTimer = null;
    var _currentListId = null;
    var _currentListUrl = null;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    function showToast() { return; }

    function clearFormErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.ajax-error-feedback, .ajax-error-summary').remove();
    }

    function showFormErrors($form, errors) {
        clearFormErrors($form);
        var shown = 0;
        $.each(errors, function (field, messages) {
            var $input = $form.find('[name="' + field + '"]');
            if ($input.length) {
                $input.addClass('is-invalid');
                $input.after('<div class="ajax-error-feedback invalid-feedback d-block">' + messages[0] + '</div>');
            } else {
                if (!$form.find('.ajax-error-summary').length) {
                    $form.prepend(
                        '<div class="ajax-error-summary alert alert-danger alert-dismissible">' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '<ul class="mb-0 mt-1" id="ajaxErrorList"></ul></div>'
                    );
                }
                $form.find('#ajaxErrorList').append('<li>' + messages[0] + '</li>');
            }
            shown++;
        });
    }

    function setButtonLoading($btn, text) {
        $btn.data('orig', $btn.html()).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span>' + (text || 'Processing…'));
    }

    function resetButton($btn) {
        var orig = $btn.data('orig');
        $btn.prop('disabled', false);
        if (orig !== undefined) { $btn.html(orig); }
    }

    function getPathFromUrl(url) {
        try { return new URL(url, window.location.origin).pathname; }
        catch (e) { return String(url || ''); }
    }

    function isAjaxableLink(link) {
        var href = link.getAttribute('href');
        if (!href || href === '#' || href.indexOf('javascript:') === 0) { return false; }
        if (link.target && link.target !== '_self') { return false; }
        if (link.hasAttribute('download')) { return false; }
        if ($(link).data('no-ajax')) { return false; }
        if (href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) { return false; }
        try { return new URL(href, window.location.origin).origin === window.location.origin; }
        catch (e) { return false; }
    }

    function loadTable(id, url, extraParams) {
        var $table = $('#' + id);
        if (!$table.length || !url) { return; }

        var searchVal = $table.closest('[data-ajax-search-id]').length
            ? $('#' + $table.closest('[data-ajax-search-id]').data('ajax-search-id')).val()
            : ($('#studentSearch').val() || '');

        var params = $.extend({
            table: 1,
            _: Date.now(),
            search: searchVal
        }, extraParams || {});

        $table.css('opacity', '0.55');

        var xhrKey = '_xhr_' + id;
        if (window[xhrKey] && window[xhrKey].readyState !== 4) {
            window[xhrKey].abort();
        }

        window[xhrKey] = $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            cache: false,
            data: params,
            success: function (html) {
                if ($('#' + id).length) { $('#' + id).html(html); }
            },
            error: function (xhr, status) {
                if (status === 'abort') { return; }
            },
            complete: function () {
                if ($('#' + id).length) { $('#' + id).css('opacity', '1'); }
            }
        });
    }

    function loadStudents(page, search) {
        var params = {};
        if (page !== undefined) { params.page = page; }
        if (search !== undefined) { params.search = search; }
        loadTable('studentTable', '/students', params);
    }

    window.loadStudents = loadStudents;
    window.loadTable = loadTable;

    function detectAndStartPolling() {
        var $listEl = $('[data-ajax-list-url]').first();
        if (!$listEl.length) { return; }

        _currentListId = $listEl.attr('id');
        _currentListUrl = $listEl.data('ajax-list-url');
        startPolling();
    }

    function startPolling() {
        stopPolling();
        if (!_currentListId || !_currentListUrl) { return; }

        _pollTimer = setInterval(function () {
            if ($('#' + _currentListId).length) {
                loadTable(_currentListId, _currentListUrl);
            } else {
                stopPolling();
            }
        }, POLL_INTERVAL);
    }

    function stopPolling() {
        if (_pollTimer !== null) {
            clearInterval(_pollTimer);
            _pollTimer = null;
        }
    }

    window.startStudentPolling = startPolling;
    window.stopStudentPolling = stopPolling;

    function getPageContent(html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var el = doc.querySelector('#ajaxPageContent');
        return el ? el.innerHTML : html;
    }

    function ajaxNavigate(url, options) {
        options = options || {};
        var target = options.target || '#ajaxPageContent';
        var $target = $(target);

        stopPolling();

        if (!$target.length) { window.location.href = url; return; }

        $target.css('opacity', '0.45');

        if (window.mfdPageRequest && window.mfdPageRequest.readyState !== 4) {
            window.mfdPageRequest.abort();
        }

        var requestUrl = url;
        var reqHash = '';
        try {
            var uObj = new URL(url, window.location.origin);
            reqHash = uObj.hash || '';
            uObj.searchParams.set('_', Date.now());
            requestUrl = uObj.toString();
        } catch (e) {
            requestUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();
        }

        window.mfdPageRequest = $.ajax({
            url: requestUrl,
            type: 'GET',
            dataType: 'html',
            cache: false,
            success: function (html, status, xhr) {
                var finalUrl = (xhr && xhr.responseURL) ? xhr.responseURL : url;
                if (reqHash && String(finalUrl).indexOf('#') === -1) {
                    finalUrl = String(finalUrl) + reqHash;
                }

                $target.html(getPageContent(html));

                var titleMatch = html.match(/<title[^>]*>(.*?)<\/title>/i);
                if (titleMatch) {
                    document.title = $('<textarea/>').html(titleMatch[1]).text();
                }

                if (options.pushState !== false && window.location.href !== finalUrl) {
                    window.history.pushState({ ajaxUrl: finalUrl, target: target }, document.title, finalUrl);
                }

                setTimeout(function () {
                    _currentListId = null;
                    _currentListUrl = null;
                    detectAndStartPolling();
                    if (_currentListId && _currentListUrl) {
                        loadTable(_currentListId, _currentListUrl);
                    }
                }, 50);

                if (reqHash) {
                    var el = document.querySelector(reqHash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        return;
                    }
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            error: function (xhr, status) {
                if (status === 'abort') { return; }
                setTimeout(function () { window.location.href = url; }, 300);
            },
            complete: function () { $target.css('opacity', '1'); }
        });
    }

    window.ajaxNavigate = ajaxNavigate;

    var DELETE_MODAL_HTML =
        '<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">' +
        '  <div class="modal-dialog modal-dialog-centered">' +
        '    <div class="modal-content">' +
        '      <div class="modal-header bg-danger text-white">' +
        '        <h5 class="modal-title"><i class="fas fa-trash me-2"></i>Confirm Delete</h5>' +
        '        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>' +
        '      </div>' +
        '      <div class="modal-body" id="deleteModalBody"></div>' +
        '      <div class="modal-footer">' +
        '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
        '        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">' +
        '          <i class="fas fa-trash me-1"></i>Delete' +
        '        </button>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>';

    function ensureDeleteModal() {
        if (!$('#deleteModal').length) { $('body').append(DELETE_MODAL_HTML); }
    }

    function ajaxDelete(deleteUrl, name, $row, listId, listUrl) {
        var label = name ? '"' + name + '"' : 'this record';

        var doDelete = function () {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                dataType: 'json',
                headers: { 'Accept': 'application/json' },
                success: function () {
                    var hasListOnPage = !!(listId && $('#' + listId).length);

                    if ($row && $row.length) {
                        $row.css('opacity', '0.3');
                        setTimeout(function () {
                            $row.remove();
                            if (listId && listUrl) {
                                loadTable(listId, listUrl);
                            } else {
                                if (_currentListId && _currentListUrl) {
                                    loadTable(_currentListId, _currentListUrl);
                                }
                            }
                        }, 150);
                    } else {
                        if (hasListOnPage && listId && listUrl) {
                            loadTable(listId, listUrl);
                        } else if (listUrl) {
                            ajaxNavigate(listUrl, { target: '#ajaxPageContent' });
                        }
                    }
                },
                error: function (xhr) {
                    var d = xhr.responseJSON;
                    alert((d && d.error) ? d.error : 'Delete failed. Please try again.');
                }
            });
        };

        if (!window.bootstrap || !bootstrap.Modal) {
            if (confirm('Delete ' + label + '? This cannot be undone.')) { doDelete(); }
            return;
        }

        ensureDeleteModal();
        $('#deleteModalBody').text('Are you sure you want to delete ' + label + '? This cannot be undone.');

        var $modal = $('#deleteModal');
        var $btn = $('#confirmDeleteBtn');
        var modal = new bootstrap.Modal($modal[0]);

        $btn.off('click').on('click', function () {
            setButtonLoading($btn, 'Deleting…');
            modal.hide();
            doDelete();
            resetButton($btn);
        });

        modal.show();
    }

    window.deleteStudent = function (id, name, ctx) {
        ctx = ctx || {};
        ajaxDelete('/students/' + id, name, ctx.$row, 'studentTable', '/students');
    };

    $(function () {

        (function () {
            var $c = $('#ajaxPageContent').first();
            var $n = $c.find('#ajaxPageContent').last();
            if ($n.length) { $c.html($n.html()); }
            $c.children('nav.navbar, footer').remove();
        })();

        $(document).off('click.mfdAjax').off('submit.mfdAjax').off('input.mfdAjax');

        $(document).on('click.mfdAjax', 'a[data-ajax-link="true"]', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.which === 2) { return; }
            if ($(this).closest('.ajax-pagination').length) { return; }
            if (!isAjaxableLink(this)) { return; }
            e.preventDefault();
            stopPolling();
            ajaxNavigate(this.href, { target: $(this).data('target') || '#ajaxPageContent' });
        });

        window.onpopstate = function () {
            stopPolling();
            ajaxNavigate(window.location.href, { pushState: false });
        };

        $(document).on('submit.mfdAjax', 'form[data-ajax="true"]', function (e) {
            e.preventDefault();

            var $form = $(this);
            var $submit = $form.find('button[type="submit"]');
            var showLoad = $form.data('loading') !== false;

            if ($form.data('submitting')) { return; }
            $form.data('submitting', true);

            clearFormErrors($form);
            if (showLoad) { setButtonLoading($submit, 'Saving…'); }

            $.ajax({
                url: $form.attr('action'),
                type: ($form.attr('method') || 'POST').toUpperCase(),
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: 'json',
                timeout: 15000,

                success: function (data) {
                    $form.data('submitting', false);
                    if (showLoad) { resetButton($submit); }

                    var redirectTo = data.redirect_url
                        || $form.data('redirect')
                        || null;

                    var delay = parseInt($form.data('redirect-delay'), 10);
                    if (isNaN(delay)) { delay = 0; }

                    if (redirectTo) {
                        setTimeout(function () {
                            ajaxNavigate(redirectTo, { target: '#ajaxPageContent' });
                        }, delay);
                    } else if ($form.data('reset-on-success')) {
                        $form[0].reset();
                        clearFormErrors($form);
                        if (_currentListId && _currentListUrl) {
                            loadTable(_currentListId, _currentListUrl);
                        }
                    }
                },

                error: function (xhr, status) {
                    $form.data('submitting', false);
                    if (showLoad) { resetButton($submit); }

                    var data = xhr.responseJSON;
                    if (xhr.status === 422 && data && data.errors) {
                        showFormErrors($form, data.errors);
                    } else if (status === 'timeout') {
                        alert('Request timed out. Please check if it was saved.');
                    } else {
                        alert((data && data.error) ? data.error : 'An error occurred. Please try again.');
                    }
                },

                complete: function () {
                    $form.data('submitting', false);
                    if (showLoad) { resetButton($submit); }
                }
            });
        });

        $(document).on('click.mfdAjax', '.js-ajax-delete', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var delUrl = $btn.data('url');
            var name = $btn.data('name');
            var listId = $btn.data('list-id') || _currentListId;
            var listUrl = $btn.data('list-url') || _currentListUrl;
            if (!delUrl) { return; }
            ajaxDelete(delUrl, name, $btn.closest('tr'), listId, listUrl);
        });

        $(document).on('click.mfdAjax', '.js-delete-student', function (e) {
            e.preventDefault();
            var $btn = $(this);
            ajaxDelete(
                '/students/' + $btn.data('id'),
                $btn.data('name'),
                $btn.closest('tr'),
                'studentTable',
                '/students'
            );
        });

        $(document).on('click.mfdAjax', '.ajax-pagination a', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            if (!href || href === '#') { return; }
            var params = {};
            try {
                var u = new URL(href, window.location.origin);
                params.page = u.searchParams.get('page') || 1;
            } catch (ex) {}
            if (_currentListId && _currentListUrl) {
                loadTable(_currentListId, _currentListUrl, params);
            }
        });

        var searchTimer = null;
        $(document).on('input.mfdAjax', '[data-ajax-search-for], #studentSearch', function () {
            clearTimeout(searchTimer);
            var $input = $(this);
            var tableId = $input.data('ajax-search-for') || 'studentTable';
            var listUrl = $('#' + tableId).data('ajax-list-url') || '/students';
            var query = $input.val();
            searchTimer = setTimeout(function () {
                loadTable(tableId, listUrl, { page: 1, search: query });
            }, 400);
        });

        detectAndStartPolling();
        if (_currentListId && _currentListUrl) {
            loadTable(_currentListId, _currentListUrl);
        }

    });

})(jQuery);
