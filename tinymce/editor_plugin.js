/**
 * appwidget plugin.
 */
(function() {
	var DOM = tinymce.DOM;
	tinymce.PluginManager.requireLangPack('appwidget');

	tinymce.create('tinymce.plugins.AppwidgetPlugin', {
		init : function(ed, url) {

			ed.addCommand ( 'mceAppwidgetInsert', function () {
				ed.windowManager.open ( {
					file: url + '/appwidget_dialog.php',
					width: 655 ,
					height: 450,
					inline: 1
				}, {
					plugin_url: url
				} );
			} );

			ed.addButton('appwidget', {
				title : '添加appwidget',
				cmd : 'mceAppwidgetInsert',
				image : url + '/images/appchina.png'
			});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('appwidget', n.nodeName == 'IMG');
			});
		},
		createControl: function ( n, cm ) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'appwidget',
				author : 'Duan xin',
				authorurl : 'http://weibo.com/dcoupe',
				infourl : 'http://i.appchina.com/',
				version : '0.1'
			};
		}
	});

	tinymce.PluginManager.add('appwidget', tinymce.plugins.AppwidgetPlugin);
})();