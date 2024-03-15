document.addEventListener(pimcore.events.postOpenObject, (e) => {
    
    const object = e.detail.object;
    const className = object.data.classes[0].name;

    //TODO FETCH DATA QUALITY PERCENTAGE FIELDS FROM CONFIGS

    if (object.search) {
        object.search.getLayout().on('add', function (el, item) {
            
            let types = ['numeric'];
            object.search.grid.columns.forEach(function (column, index) {
                let config = column.getInitialConfig();
                if ('layout' in config && types.includes(config.layout.type)) {
                    let originalRenderer = column.renderer;
                    column.renderer = function (key, value, metaData, record) {
                        //metaData.tdCls += " mandatory-data";
                        
                        let cellValue = originalRenderer(key, value, metaData, record);
                        if (cellValue) {
                            if (column.text == "DataQualityPercent") {
                                //FETCH DIV FROM CONTROLLER
                                if (cellValue >= 90) {
                                    cellValue = ''.concat('<div class="rounded-full bg-green-500 text-center text-white font-semibold">', cellValue, '%</div>');
                                } else if (cellValue >= 40 && cellValue < 90) {
                                    cellValue = ''.concat('<div class="rounded-full bg-yellow-500 text-center text-white font-semibold">', cellValue, '%</div>');
                                } else {
                                    cellValue = ''.concat('<div class="rounded-full bg-red-500 text-center text-white font-semibold">', cellValue, '%</div>');
                                }
                            }
                        }
                        return cellValue;
                    }
                }
            });
        });
    }

});