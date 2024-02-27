  document.addEventListener(pimcore.events.prepareClassLayoutContextMenu, (allowedTypes) => {
    for (let layout in allowedTypes) {
      if (allowedTypes[layout] !== undefined && allowedTypes[layout].length > 0) {
        allowedTypes[layout].push('dataQuality')
      }
    }
    return allowedTypes;
  });

  document.addEventListener(pimcore.events.postOpenObject, (e) => {
    const object = e.detail.object;
    if (object.data.general.o_classId !== 'DQC' && (object.data.general.o_type === 'object' || object.data.general.o_type === 'variant')) {
      fetch('/admin/data-quality/check-class-has-data-quality/' + object.id)
        .then((response) => {
          if (response.status === 200) {
            var path = '/admin/data-quality/index/' + object.id;
            var html = '<iframe src="' + path + '" style="width: 100%; height: 100%; border: 0;"></iframe>';

            this.tab = Ext.create('Ext.panel.Panel', {
              border: false,
              autoScroll: true,
              closable: false,
              iconCls: 'pimcore_icon_charty',
              bodyCls: 'pimcore_overflow_scrolling',
              html: html,
              tbar: {
                items: [
                  '->', // Fill
                  {
                    xtype: 'button',
                    text: t('refresh'),
                    iconCls: 'pimcore_icon_reload',
                    handler: function () {
                      var key = "object_" + object.id;
                      if (pimcore.globalmanager.exists(key)) {
                        var objectTab = pimcore.globalmanager.get(key);
                        objectTab.saveToSession(function () {
                          this.tab.setHtml(html);
                        }.bind(this));
                      }
                    }.bind(this)
                  }
                ]
              }
            });
            // this adds the pimcore tab
            object.tabbar.add([this.tab]);
            pimcore.layout.refresh();
          }
        })
        .catch(() => {
          // do nothing and show nothing
        });
    }
  });

