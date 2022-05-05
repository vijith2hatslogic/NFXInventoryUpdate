import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';

export default class nfxInventoryUpdatePlugin extends Plugin {

    static options = {
        frmEanSearch: '#frmEanSearch',
        frmInventoryUpdate: '#frmInventoryUpdate',
        eanErrorLabel: '#eanErrorLabel',
        successMessageLabel: '#successMessageLabel',
        errorMessageLabel: '#errorMessageLabel',
        inventoryUpdateWrapper: '#inventoryUpdateWrapper',
        formResultContainer: '#formResultContainer',
        formSearchContainer: '#formSearchContainer',
        backToSearch: '#backToSearch'
    };

    init() {
        this._httpClient = new HttpClient();
        this.eanErrorLabel = $(this.options.eanErrorLabel);
        this.successMessageLabel = $(this.options.successMessageLabel);
        this.errorMessageLabel = $(this.options.errorMessageLabel);
        this.inventoryUpdateWrapper = $(this.options.inventoryUpdateWrapper);
        this.formResultContainer = $(this.options.formResultContainer);
        this.formSearchContainer = $(this.options.formSearchContainer);
        this._registerEvents();
    }

    _registerEvents() {
        const me = this;

        $(this.el).on('submit', this.options.frmEanSearch, function (event) {
            event.preventDefault();
            me._submitEanSearchForm(event);
        });

        $(this.el).on('submit', this.options.frmInventoryUpdate, function (event) {
            event.preventDefault();
            me._updateStock(event);
        });

        $(this.el).on('click', this.options.backToSearch, function (event) {
            event.preventDefault();
            me._backToEanSearch(event);
        });
    }

    _submitEanSearchForm(event) {
        const me = this;
        var url = $(event.target).attr('action');
        var eanNumber = $(event.target).find('#eanNumber').val();

        this.inventoryUpdateWrapper.addClass("processing");

        const data = JSON.stringify({
            eanNumber: eanNumber,
        });

        this._httpClient.post(url, data, (res) => {
            const response = JSON.parse(res);
            
            me._updateErrorMessage(response);

            this.inventoryUpdateWrapper.removeClass("processing");

            if (response.content) {
                this.formResultContainer.html(response.content);
                //this.formResultContainer.show();
                //this.formSearchContainer.hide();
                this.inventoryUpdateWrapper.addClass("product-form");
            } else {
                //this.formSearchContainer.show();
                this.inventoryUpdateWrapper.removeClass("product-form");
            }

        });

    }

    _updateStock(event) {
        const me = this;
        var url = $(event.target).attr('action');
        var stock = $(event.target).find('#stock').val();
        var productId = $(event.target).find('#productId').val();
        var currentStock = $(event.target).find('#currentStock').val();

        this.inventoryUpdateWrapper.addClass("processing");

        const data = JSON.stringify({
            productId: productId,
            currentStock: currentStock,
            stock: stock,
        });

        this._httpClient.post(url, data, (res) => {
            const response = JSON.parse(res);

            this.inventoryUpdateWrapper.removeClass("processing");
            this.inventoryUpdateWrapper.addClass("product-form");
            this.formResultContainer.html(response.content);

            me._refreshContiners();

            me._updateMessage(response);

        });

    }

    _backToEanSearch(event) {
        const me = this;
        //this.formResultContainer.hide();
        //this.formSearchContainer.show();

        this.inventoryUpdateWrapper.removeClass("product-form");
    }

    _updateErrorMessage(response) {

        this.eanErrorLabel.html(response.message);

        if (response.status == false) {
            this.eanErrorLabel.show();
        } else {
            this.eanErrorLabel.hide();
        }

    }

    _updateMessage(response) {

        if (response.status == true) {
            this.successMessageLabel.html(response.message);
            this.successMessageLabel.show();

            this.errorMessageLabel.html("");
            this.errorMessageLabel.hide();

        } else {
            this.errorMessageLabel.html(response.message);
            this.errorMessageLabel.show();

            this.successMessageLabel.html("");
            this.successMessageLabel.hide();
        }

    }

    _refreshContiners() {
        this.successMessageLabel = this.formResultContainer.find(this.options.successMessageLabel);
        this.errorMessageLabel = this.formResultContainer.find(this.options.errorMessageLabel);
    }

}

