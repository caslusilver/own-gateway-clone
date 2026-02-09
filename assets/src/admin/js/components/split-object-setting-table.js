jQuery(function ($) {

    // $('.select-split-wallet-type').on('change', function () {
    //     const row = $(this).closest('tr');
    //     const beforeElement = $(`.wallet-value-before`, row);
    //     const afterElement = $(`.wallet-value-after`, row);
    //     if ($(this).val() === 'percentage') {
    //         beforeElement.hide();
    //         afterElement.show();
    //         return;
    //     }
    //     beforeElement.show();
    //     afterElement.hide();
    // });

    const elements = {
        noSplitWalletsRow: $('.object-setting-table__no-wallets')
    };

    const classNames = {
        noSplitWalletsRowHidden: 'object-setting-table__no-wallets--hidden'
    };

    function maybeShowNoSplitWalletsRow() {
        elements.noSplitWalletsRow.toggleClass(classNames.noSplitWalletsRowHidden, $('.object-setting-table__row').length > 0);
    }

    $('.remove-wallet-row').on('click', function(event) {
        event.preventDefault();
        const index = $(this).data('index');
        $('.object-setting-table__row[data-index="' + index + '"]').remove();
        maybeShowNoSplitWalletsRow()
    });

    $('#add-wallet').on('click', function(event) {
        event.preventDefault();
        let rowCount = $('.object-setting-table__row').last().data('index');
        rowCount = (typeof rowCount === 'undefined') ? 0 : rowCount+1;

        const template = $('#wallet-row-template').html().replace(/{index}/g, rowCount);
        const row = $(template);

        $('#split-wallet-table-list').append(row);

        maybeShowNoSplitWalletsRow();

        $('.select-split-wallet-type', row).on('change', function() {
            const container = $(this).closest('tr');
            const beforeElement = $('.wallet-value-before', container);
            const afterElement = $('.wallet-value-after', container);

            if ($(this).val() === 'percentage') {
                beforeElement.hide();
                afterElement.show();
            } else {
                beforeElement.show();
                afterElement.hide();
            }
        });
        $('.remove-wallet-row', row).on('click', function(event) {
            event.preventDefault();
            const index = $(this).data('index');
            $('.object-setting-table__row[data-index="' + index + '"]').remove();
            maybeShowNoSplitWalletsRow();
        });
    });
});