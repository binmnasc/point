{
    "menu": {
        "config": {
            "development_mode": false
        },
        "structure": [
            {
                "id": "dashboard",
                "title": "Dashboard",
                "icon": "dashboard",
                "type": "link",
				"show_in_sidebar": true,
                "auto_load_subview": false,
                "viewpath": "dashboard/",
                "header": {
                    "auto_generate": false,
                    "show_save": false,
                    "header_label": "",
                    "header_title": ""
                }
            },
            {
                "id": "dropdown_default",
                "title": "Maps",
                "icon": "maps",
                "type": "dropdown",
                "auto_load_subview": false,
                "submenu": [
                    {
                        "id": "dropdown_submenu_holder",
                        "type": "holder"
                    },
                    {
                        "id": "sidebar_add_new_btn",
                        "title": "Add New",
                        "type": "button"
                    }
                ],
                "viewpath": "maps/",
                "header": {
                    "auto_generate": true,
                    "show_save": true,
                    "header_label": "",
                    "header_title": ""
                },
                "views": [
                    {
                        "id": "maps_setup",
                        "title": "Setup",
                        "icon": "maps",
                        "submenu": [
                            {
                                "id": "maps_setup_view",
                                "title": "Maps Setup View",
								"auto_load_components": true,
                                "view": "maps_setup"
                            }
                        ]
                    },
					{
                        "id": "maps_markers",
                        "title": "Markers",
                        "icon": "markers",
                        "submenu": [
                            {
                                "id": "maps_markers_view",
                                "title": "Maps Markers View",
								"auto_load_components": true,
                                "view": "maps_markers"
                            }
                        ]
                    },
					{
                        "id": "maps_drawing",
                        "title": "Shapes",
                        "icon": "drawing",
                        "submenu": [
                            {
                                "id": "maps_drawing_view",
                                "title": "Maps Drawing View",
								"auto_load_components": true,
                                "view": "maps_drawing"
                            }
                        ]
                    },
					{
                        "id": "maps_settings",
                        "title": "Settings",
                        "icon": "settings",
                        "submenu": [
                            {
                                "id": "maps_settings_view",
                                "title": "Maps Settings View",
								"auto_load_components": true,
                                "view": "maps_settings"
                            }
                        ]
                    },
					{
                        "id": "maps_controls",
                        "title": "Controls",
                        "icon": "controls",
                        "submenu": [
                            {
                                "id": "maps_controls_view",
                                "title": "Maps Controls View",
								"auto_load_components": true,
                                "view": "maps_controls"
                            }
                        ]
                    },
					{
                        "id": "maps_advanced",
                        "title": "Advanced",
                        "icon": "advanced",
                        "submenu": [
                            {
                                "id": "maps_advanced_view",
                                "title": "Maps Advanced View",
								"auto_load_components": true,
                                "view": "maps_advanced"
                            }
                        ]
                    },
					{
                        "id": "maps_developers",
                        "title": "Developers",
                        "icon": "code",
                        "submenu": [
                            {
                                "id": "maps_developers_view",
                                "title": "Maps Developers View",
								"auto_load_components": true,
                                "view": "maps_developers"
                            }
                        ]
                    }
                ]
            },
			{
                "id": "markers",
                "title": "Markers",
                "icon": "markers",
                "type": "link",
				"show_in_sidebar": true,
                "auto_load_subview": true,
                "viewpath": "markers/",
                "header": {
                    "auto_generate": true,
                    "show_save": false,
                    "header_label": "CURRENTLY VIEWING",
                    "header_title": "LOCATION MARKER MANAGEMENT"
                },
				"views": [
                    {
                        "id": "markers_view",
                        "title": "Markers",
                        "icon": "markers",
                        "submenu": [
                            {
                                "id": "marker_packs",
                                "title": "Marker Packs",
								"auto_load_components": false,
                                "view": "marker_packs_view"
                            },
							{
                                "id": "marker_pack_upload",
                                "title": "Upload Marker Pack",
								"auto_load_components": true,
                                "view": "upload_marker_pack_view"
                            },
							{
                                "id": "marker_custom_upload",
                                "title": "Custom Markers",
								"auto_load_components": true,
                                "view": "marker_custom_upload_view"
                            }
                        ]
                    }
                ]
            },
			{
                "id": "import",
                "title": "Import",
                "icon": "import",
                "type": "link",
				"show_in_sidebar": true,
                "auto_load_subview": true,
                "viewpath": "import/",
                "header": {
                    "auto_generate": true,
                    "show_save": false,
                    "header_label": "CURRENTLY VIEWING",
                    "header_title": "MAP IMPORT MANAGER"
                },
				"views": [
                    {
                        "id": "import_view",
                        "title": "Importer",
                        "icon": "import",
                        "submenu": [
                            {
                                "id": "map_importer",
                                "title": "Map Import Manager",
								"auto_load_components": false,
                                "view": "map_importer_view"
                            },
							{
                                "id": "marker_csv_importer",
                                "title": "Marker CSV Import Manager",
								"auto_load_components": false,
                                "view": "marker_csv_importer_view"
                            }
                        ]
                    }
                ]
            }
        ]
    }
}