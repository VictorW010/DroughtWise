
function UEDynamicFilters(){
	
	var g_objFilters, g_objGrid, g_filtersData, g_urlBase;
	var g_urlAjax, g_lastGridAjaxCall, g_cache = {};
	
	var g_types = {
		CHECKBOX:"checkbox"
	};
	
	var g_vars = {
		CLASS_DIV_DEBUG:"uc-div-ajax-debug",
		CLASS_GRID:"uc-filterable-grid",		
		DEBUG_AJAX_OPTIONS: false,
		CLASS_PREDICTIVE_CACHE:"uc-predictive-cache-next",
		cachePredictiveKeys:{},
		handleTrashold:null
	};
	
	var g_options = {
		is_cache_enabled:true,
		is_predictive_cache_enabled:false,
		ajax_reload: false,
		widget_name: null,
		predictive_trashold_time: 3000
	};
	
	
	/**
	 * console log some string
	 */
	function trace(str){
		console.log(str);
	}
	
	function ________GENERAL_______________(){}
	
	
	/**
	 * run function with trashold
	 */
	function runWithTrashold(func, time){
		
		if(!time)
			var time = g_options.predictive_trashold_time;
			
		if(g_vars.handle)
			clearTimeout(g_vars.handleTrashold);
		
		g_vars.handleTrashold = setTimeout(func, g_options.predictive_trashold_time);
		
	};
	
	
	/**
	 * add url param
	 */
	function addUrlParam(url, param, value){
		
		if(url.indexOf("?") == -1)
			url += "?";
		else
			url += "&";
		
		if(typeof value == "undefined")
			url += param;
		else	
			url += param + "=" + value;
		
		return(url);
	}
	
	
	/**
	 * get object property
	 */
	function getVal(obj, name, defaultValue){
		
		if(!defaultValue)
			var defaultValue = "";
		
		var val = "";
		
		if(!obj || typeof obj != "object")
			val = defaultValue;
		else if(obj.hasOwnProperty(name) == false){
			val = defaultValue;
		}else{
			val = obj[name];			
		}
		
		return(val);
	}
	
	/**
	 * turn string value ("true", "false") to string 
	 */
	function strToBool(str){
		
		switch(typeof str){
			case "boolean":
				return(str);
			break;
			case "undefined":
				return(false);
			break;
			case "number":
				if(str == 0)
					return(false);
				else 
					return(true);
			break;
			case "string":
				str = str.toLowerCase();
						
				if(str == "true" || str == "1")
					return(true);
				else
					return(false);
				
			break;
		}
		
		return(false);
	};
	
	/**
	 * get closest grid to some object
	 */
	function getClosestGrid(objSource){
		
		//in case there is only one grid - return it
		if(g_objGrid)
			return(g_objGrid);
		
		//in case there are nothing:
		var objGrids = jQuery("."+ g_vars.CLASS_GRID);
		
		if(objGrids.length == 0)
			return(null);
		
		//get from current section
		var objSection = objSource.parents("section");
		
		var objGrid = objSection.find("."+ g_vars.CLASS_GRID);
		
		if(objGrid.length == 1)
			return(objGrid);
		
		var objPrevSection = objSection;
		var objNextSection = objSection;
		
		//get from previous section
		do{
			objPrevSection = objPrevSection.prev();
			objNextSection = objNextSection.next();
			
			objGrid = objPrevSection.find("."+ g_vars.CLASS_GRID);
			if(objGrid.length == 1)
				return(objGrid);
			
			objGrid = objNextSection.find("."+ g_vars.CLASS_GRID);
			if(objGrid.length == 1)
				return(objGrid);
			
		}while(objNextSection.length != 0 && objNextSection != 0);
		
		//return first grid in the list
		
		var objFirstGrid = jQuery(objGrids[0]);
		return(objFirstGrid);
	}
	
	
	/**
	 * add filter object to grid
	 */
	function bindFilterToGrid(objGrid, objFilter){
		
		var arrFilters = objGrid.data("filters");
		
		if(!arrFilters)
			arrFilters = [];
		
		arrFilters.push(objFilter);
		
		objGrid.data("filters", arrFilters);
		
	}
	
	/**
	 * 
	 * get element widget id from parent wrapper
	 */
	function getElementWidgetID(objElement){
		
		if(!objElement || objElement.length == 0)
			throw new Error("Element not found");
		
		//get widget id
		
		var objWidget = objElement.parents(".elementor-widget");
		
		if(objWidget.langth == 0)
			throw new Error("Element parent not found");
		
		var widgetID = objWidget.data("id");
		
		if(!widgetID)
			throw new Error("widget id not found");
		
		return(widgetID);
	}
	
	
	/**
	 * get element layout data
	 */
	function getElementLayoutData(objElement){
		
		if(!objElement || objElement.length == 0)
			throw new Error("Element not found");
		
		//get widget id
		
		var objWidget = objElement.parents(".elementor-widget");
		
		if(objWidget.langth == 0)
			throw new Error("Element parent not found");
		
		var widgetID = objWidget.data("id");
		
		if(!widgetID)
			throw new Error("widget id not found");
			
		//get layout id
		var objLayout = objWidget.parents(".elementor");
		
		if(objLayout.length == 0)
			throw new Error("layout not found");
		
		var layoutID = objLayout.data("elementor-id");
		
		var output = {};
		
		output["widgetid"] = widgetID;
		output["layoutid"] = layoutID;
		
		return(output);
	}
	
	
	
	function ________FILTERS_______________(){}
	
	
	/**
	 * get filter type
	 */
	function getFilterType(objFilter){
		
		if(objFilter.is(":checkbox"))
			return(g_types.CHECKBOX);
		
		return(null);
	}
	
	
	/**
	 * clear filter
	 */
	function clearFilter(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				objFilter.prop("checked", false);
			break;
		}
		
	}
	
	
	/**
	 * clear filters
	 */
	function clearFilters(checkActive){
		
		jQuery.each(g_objFilters,function(index, filter){
			
			var objFilter = jQuery(filter);
			
			if(checkActive == true){
				
				var isActive = objFilter.data("active");
				if(isActive == "yes")
					return(true);
			}
			
			clearFilter(objFilter);
			
		});
		
	}
	
	/**
	 * get filter data
	 */
	function getFilterData(objFilter){
		
		var objData = {};
		
		var type = getFilterType(objFilter);
		
		var type = objFilter.data("type");
		
		objData["type"] = type;
		
		switch(type){
			case "term":
				var taxonomy = objFilter.data("taxonomy");
				var term = objFilter.data("term");
				
				objData["taxonomy"] = taxonomy;
				objData["term"] = term;
			break;
			default:
				throw new Error("getFilterData error: wrong data type: "+type);
			break;
		}
		
		return(objData);
	}
	
	/**
	 * check if the filter selected
	 */
	function isFilterSelected(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				
				var isSelected = objFilter.is(":checked");
				
				return(isSelected);
				
			break;
			default:
				throw new Error("isFilterSelected error. wrong type: "+type);
			break;
		}
		
		
		return(false);
	}
	
	
	/**
	 * get all selected filters
	 */
	function getSelectedFilters(){
		
		var objSelected = [];
		
		jQuery.each(g_objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var isSelected = isFilterSelected(objFilter);
			
			if(isSelected == true)
				objSelected.push(objFilter);
			
		});
		
		
		return(objSelected);
	}
	
	function ________PAGINATION_FILTER______(){}
	
	/**
	 * check if the filter is pagination
	 */
	function isPaginationFilter(objFilter){
		
		if(objFilter.hasClass("uc-filter-pagination"))
			return(true);
		
		return(false);
	}
	
	/**
	 * get pagination selected url or null if is current
	 */
	function getPaginationSelectedUrl(objPagination, isPredictive){
		
		if(isPredictive == true){
			
			var objCurrentLink = objPagination.find("a."+g_vars.CLASS_PREDICTIVE_CACHE);
			
			if(objCurrentLink.length == 0)
				return(null);
		}
		else
			var objCurrentLink = objPagination.find("a.current");
		
		if(objCurrentLink.length == 0)
			return(null);
		
		var url = objCurrentLink.attr("href");
		
		if(!url)
			return(null);
		
		return(url);
	}
	
	
	
	/**
	 * on ajax pagination click
	 */
	function onAjaxPaginationLinkClick(event){
		
		var objLink = jQuery(this);
		
		var objPagination = objLink.parents(".uc-filter-pagination");
						
		var objLinkCurrent = objPagination.find(".current");
		
		objLinkCurrent.removeClass("current");
		
		objLink.addClass("current");
				
		var objGrid = objPagination.data("grid");
		
		if(!objGrid || objGrid.length == 0)
			throw new Error("Grid not found!");
		
		//run the ajax, prevent default
		refreshAjaxGrid(objGrid);
		
		event.preventDefault();
		return(false);
	}
	
	
	function ________DATA_______________(){}
	
	 
	/**
	 * get filters data array
	 */
	function getArrFilterData(){
		
		var objFilters = getSelectedFilters();
		
		if(objFilters.length == 0)
			return([]);
		
		var arrData = [];
		
		jQuery.each(objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var objFilterData = getFilterData(objFilter);
			
			arrData.push(objFilterData);
		});
		
		return(arrData);
	}
	
	/**
	 * consolidate filters data
	 */
	function consolidateFiltersData(arrData){
		
		if(arrData.length == 0)
			return([]);
		
		//consolidate by taxonomies
		
		var objTax = {};
		
		jQuery.each(arrData, function(index, item){
			
			switch(item.type){
				case "term":
					
					var taxonomy = item.taxonomy;
					var term = item.term;
					
					if(objTax.hasOwnProperty(taxonomy) == false)
						objTax[taxonomy] = [];
					
					objTax[taxonomy].push(term);
					
				break;
				default:
					throw new Error("consolidateFiltersData error: wrong type: "+item.type);
				break;
			}
			
		});
		
		var arrConsolidated = {};
		arrConsolidated["terms"] = objTax;
		
		return(arrConsolidated);
	}
	
	/**
	 * build terms query
	 */
	function buildQuery_terms(objTax){
		
		var query = "";
		
		jQuery.each(objTax, function(taxonomy, arrTerms){
			
			var strTerms = arrTerms.join(".");
			if(!strTerms)
				return(true);

			//separator
			
			if(query)
				taxonomy += ";";
			
			//query
			
			query += taxonomy + "~" + strTerms;
		});
		
		return(query);
	}
	
	
	/**
	 * build url query from the filters
	 * example:
	 * ucfilters=product_cat~shoes,dress;cat~123,43;
	 */
	function buildUrlQuery(){
				
		var arrData = getArrFilterData();
		
		if(arrData.length == 0)
			return("");
		
		var queryFilters = "";
		
		var arrConsolidated = consolidateFiltersData(arrData);
		
		jQuery.each(arrConsolidated, function(type, objItem){
			
			switch(type){
				case "terms":
					var queryTerms = buildQuery_terms(objItem);
					
					if(queryFilters)
						queryFilters += ";";
					
					queryFilters += queryTerms;
				break;
			}
			
		});
		
		//return query
		
		var query = "ucfilters=" + queryFilters;
		
		return(query);
	}
	
	/**
	 * get redirect url
	 */
	function getRedirectUrl(query){
		
		if(!g_urlBase)
			throw new Error("getRedirectUrl error - empty url");
		
		var url = g_urlBase;
		
		if(!query)
			return(url);
		
		var posQ = url.indexOf("?");
		
		if(posQ == -1)
			url += "?";
		else
			url += "&";
		
		url += query;
		
		return(url);
	}
	
	function ________AJAX_CACHE_________(){}

	/**
	 * get ajax url
	 */
	function getAjaxCacheKeyFromUrl(ajaxUrl){
		
		var key = ajaxUrl;
		
		key = key.replace(g_urlAjax, "");
		key = key.replace(g_urlBase, "");
		
		//replace special signs
		key = replaceAll(key, "/","");
		key = replaceAll(key, "?","_");
		key = replaceAll(key, "&","_");
		key = replaceAll(key, "=","_");
		
		return(key);
	}
	
	/**
	 * get ajax cache key
	 */
	function getAjaxCacheKey(ajaxUrl, action, objData){
		
	    if(g_options.is_cache_enabled == false)
	    	return(false);
	    
	    //cache only by url meanwhile
	    
	    if(jQuery.isEmptyObject(objData) == false)
	    	return(false);
	    
	    if(action)
	    	return(false);
	    
	    var cacheKey = getAjaxCacheKeyFromUrl(ajaxUrl);
	    
	    if(!cacheKey)
	    	return(false);
	    
	    return(cacheKey);
	}
	
	
	/**
	 * cache ajax response
	 */
	function cacheAjaxResponse(ajaxUrl, action, objData, response){
		
	    var cacheKey = getAjaxCacheKey(ajaxUrl, action, objData);
	    
	    if(!cacheKey)
	    	return(false);
	    
	    //some precoutions for overload
	    if(g_cache.length > 100)
	    	return(false);
	    
	    g_cache[cacheKey] = response;
	    
	}
	
	
	function ________PREDICTIVE_______________(){}

	/**
	 * get predictive url
	 */
	function setPredictiveCache_pagination(objPagination){
		
		if(g_options.is_predictive_cache_enabled == false)
			return(false);
		
		//check if already on the page
		var objPredictive = jQuery("." + g_vars.CLASS_PREDICTIVE_CACHE);
		
		if(objPredictive.length != 0)
			return(false);
				
		var objLinks = objPagination.find("a:not("+g_vars.CLASS_PREDICTIVE_CACHE+")");
		
		if(objLinks.length == 0)
			return(null);
		
		var paginationID = objPagination.attr("id");
		
		var urlNextLink = null;
		
		jQuery.each(objLinks, function(index, link){
			
			var objLink = jQuery(link);
			
			if(objLink.hasClass("current"))
				return(true);
						
			var linkID = paginationID + "_" + objLink.index();
			
			if(g_vars.cachePredictiveKeys.hasOwnProperty(linkID))
				return(true);
			
			//set next predictive
			
			objLink.addClass(g_vars.CLASS_PREDICTIVE_CACHE);
			
			//remember predictive item
			g_vars.cachePredictiveKeys[linkID] = true;
			
			//set only once
			return(false);
		});
		
	}
	
	
	/**
	 * check the predictive request
	 */
	function checkPredictiveRequest(){
		
		if(g_options.is_predictive_cache_enabled == false)
			return(false);
		
		//check if already on the page
		var objPredictive = jQuery("." + g_vars.CLASS_PREDICTIVE_CACHE);
		
		if(objPredictive.length == 0)
			return(false);
			
		if(objPredictive.length > 1){
			objPredictive.removeClass(g_vars.CLASS_PREDICTIVE_CACHE);
			return(false);
		}
		
		//run with delay
				
		runWithTrashold(function(){
			
			if(objPredictive.length == 0)
				return(false);
			
			objPredictive.trigger("predictive");
			
		});
		
		
	}

	/**
	 * on predictive link click
	 */
	function onAjaxPaginationPredictive(){
		
		var objLink = jQuery(this);
		
		var objPagination = objLink.parents(".uc-filter-pagination");
		
		var objGrid = objPagination.data("grid");
		
		if(!objGrid || objGrid.length == 0)
			return(false);
		
		var ajaxOptions = getGridAjaxOptions([objPagination], objGrid, true);
		
		if(!ajaxOptions)
			return(false);

		var ajaxUrl = ajaxOptions["ajax_url"];
				
		removePredictiveItem();
		
		ajaxRequest(ajaxUrl);
	}


	/**
	 * remove predicfive from all
	 */
	function removePredictiveItem(){
		
		var objPredictive = jQuery("."+g_vars.CLASS_PREDICTIVE_CACHE);
		
		if(objPredictive.length == 0)
			return(false);
		
		objPredictive.removeClass(g_vars.CLASS_PREDICTIVE_CACHE);
		
	}
	
	
	function ________AJAX_RESPONSE_______________(){}

	/**
	 * replace the grid debug
	 */
	function operateAjax_setHtmlDebug(response, objGrid){
				
		//replace the debug
		var htmlDebug = getVal(response, "html_debug");
				
		if(!htmlDebug)
			return(false);
		
		var gridParent = objGrid.parent();
				
		var objDebug = objGrid.siblings(".uc-debug-query-wrapper");
		
		if(objDebug.length == 0)
			return(false);
		
		objDebug.replaceWith(htmlDebug);
	}
	
	
	/**
	 * set html grid from ajax response
	 */
	function operateAjax_setHtmlGrid(response, objGrid){
		
		if(objGrid.length == 0)
			return(false);
		
		objItemsWrapper = getGridItemsWrapper(objGrid);
		
		var htmlItems = getVal(response, "html_items");
		
		objItemsWrapper.html(htmlItems);
		
		operateAjax_setHtmlDebug(response, objGrid);
		
	}
	
	
	/**
	 * replace filters html
	 */
	function operateAjax_setHtmlWidgets(response, objFilters){
		
		if(!objFilters)
			return(false);
		
		if(objFilters.length == 0)
			return(false);
		
		var objHtmlWidgets = getVal(response, "html_widgets");
		
		if(!objHtmlWidgets)
			return(false);
		
		if(objHtmlWidgets.length == 0)
			return(false);
		
		jQuery.each(objFilters, function(index, objFilter){
			
			var widgetID = getElementWidgetID(objFilter);
			
			if(!widgetID)
				return(true);
			
			var html = getVal(objHtmlWidgets, widgetID);
			
			var objHtml = jQuery(html);
			
			var htmlInner = objHtml.html();
			
			objFilter.html(htmlInner);
		});
		
	}
	
	/**
	 * scroll to grid top
	 */
	function scrollToGridTop(objGrid){
		
		var gapTop = 150;
		
		var gridOffset = objGrid.offset().top;
		
		var gridTop = gridOffset - gapTop;
		
		if(gridTop < 0)
			gridTop = 0;
		
		//check if the grid top is visible
		
		var currentPos = jQuery(window).scrollTop();
		
		if(currentPos <= gridOffset)
			return(false);
		
		window.scrollTo({ top: gridTop, behavior: 'smooth' });
		
	}
	
	
	/**
	 * operate the response
	 */
	function operateAjaxRefreshResponse(response, objGrid, objFilters){
		
		operateAjax_setHtmlGrid(response, objGrid);
		
		operateAjax_setHtmlWidgets(response, objFilters);
		
		objGrid.trigger("uc_ajax_refreshed");
		
		setTimeout(function(){
			
			scrollToGridTop(objGrid);
			
		},200);
		
	}
	
	
	/**
	 * replace all occurances
	 */
	function replaceAll(text, from, to){
		
		return text.split(from).join(to);		
	};
	
	
	
	
	/**
	 * get response from ajax cache
	 */
	function getResponseFromAjaxCache(ajaxUrl, action, objData){
	
	    var cacheKey = getAjaxCacheKey(ajaxUrl, action, objData);
	    
	    if(!cacheKey)
	    	return(false);
		
	    var response = getVal(g_cache, cacheKey);
	    
	    return(response);
	}
	
	
	function ________AJAX_______________(){}
	
	/**
	 * show ajax error, should be something visible
	 */
	function showAjaxError(message){
		
		alert(message);
		
	}
	
	/**
	 * get the debug object
	 */
	function getDebugObject(){
		
		var objGrid = g_lastGridAjaxCall;
		
		if(!objGrid)
			return(null);
		
		var objDebug = objGrid.find("."+g_vars.CLASS_DIV_DEBUG);
		
		if(objDebug.length)
			return(objDebug);
		
		//insert if not exists
		
		objGrid.after("<div class='"+g_vars.CLASS_DIV_DEBUG+"' style='padding:10px;display:none;background-color:#D8FCC6'></div>");
		
		var objDebug = jQuery("body").find("."+g_vars.CLASS_DIV_DEBUG);
		
		return(objDebug);
	}
	
	
	/**
	 * show ajax debug
	 */
	function showAjaxDebug(str){
		
		str = jQuery.trim(str);
		
		if(!str || str.length == 0)
			return(false);
		
		var objStr = jQuery(str);
		
		if(objStr.find("header").length || objStr.find("body").length){
			str = "Wrong ajax response!";
		}
		
		var objDebug = getDebugObject();
		
		if(!objDebug || objDebug.length == 0){
			
			alert(str);
			
			throw new Error("debug not found");
		}
		
		objDebug.show();
		objDebug.html(str);
		
	}
	
	
	/**
	 * small ajax request
	 */
	function ajaxRequest(ajaxUrl, action, objData, onSuccess){
		
		if(!objData)
			var objData = {};
		
		if(typeof objData != "object")
			throw new Error("wrong ajax param");
		
		//check response from cache
		var responseFromCache = getResponseFromAjaxCache(ajaxUrl, action, objData);
		
		if(responseFromCache){
			
			//simulate ajax request
			setTimeout(function(){
				onSuccess(responseFromCache);
			}, 300);
			
			return(false);
		}		
		
		var ajaxData = {};
		ajaxData["action"] = "unlimitedelements_ajax_action";
		ajaxData["client_action"] = action;
		
		var ajaxtype = "get";
		
		if(objData){
			ajaxData["data"] = objData;
			ajaxtype = "post";
		}
		
		var ajaxOptions = {
				type:ajaxtype,
				url:ajaxUrl,
				success:function(response){
					
					if(!response){
						showAjaxError("Empty ajax response!");
						return(false);					
					}
										
					if(typeof response != "object"){
						
						try{
							
							response = jQuery.parseJSON(response);
							
						}catch(e){
							
							showAjaxDebug(response);
							
							showAjaxError("Ajax Error!!! not ajax response");
							return(false);
						}
					}
					
					if(response == -1){
						showAjaxError("ajax error!!!");
						return(false);
					}
					
					if(response == 0){
						showAjaxError("ajax error, action: <b>"+action+"</b> not found");
						return(false);
					}
					
					if(response.success == undefined){
						showAjaxError("The 'success' param is a must!");
						return(false);
					}
					
					
					if(response.success == false){
						showAjaxError(response.message);
						return(false);
					}
					
					cacheAjaxResponse(ajaxUrl, action, objData, response);
					
					if(typeof onSuccess == "function"){
												
						onSuccess(response);
					}
					
				},
				error:function(jqXHR, textStatus, errorThrown){
										
					switch(textStatus){
						case "parsererror":
						case "error":
							
							//showAjaxError("parse error");
							
							showAjaxDebug(jqXHR.responseText);
							
						break;
					}
				}
		}
		
		if(ajaxtype == "post"){
			ajaxOptions.dataType = 'json';
			ajaxOptions.data = ajaxData
		}
		
		jQuery.ajax(ajaxOptions);
		
	}
	
	
	
	/**
	 * get grid items wrapper
	 */
	function getGridItemsWrapper(objGrid){
		
		if(objGrid.hasClass("uc-items-wrapper"))
			return(objGrid);
		
		var objItemsWrapper = objGrid.find(".uc-items-wrapper");
		
		if(objItemsWrapper.length == 0)
			throw new Error("Missing items wrapper - with class: uc-items-wrapper");
		
		return(objItemsWrapper);
	}
	
	
	/**
	 * set ajax loader
	 */
	function showAjaxLoader(objElement){
		
		objElement.addClass("uc-ajax-loading");		
	}
	
	/**
	 * hide ajax loader
	 */
	function hideAjaxLoader(objElement){
		
		objElement.removeClass("uc-ajax-loading");		
	}
	
	
	/**
	 * show multiple ajax loader
	 */
	function showMultipleAjaxLoaders(objElements, isShow){
		
		if(!objElements)
			return(false);
		
		if(objElements.length == 0)
			return(false);
		
		jQuery.each(objElements,function(index, objElement){
			
			if(isShow == true)
				showAjaxLoader(objElement);
			else
				hideAjaxLoader(objElement);
		});
		
	}
	
		
	
	
	/**
	 * refresh ajax grid
	 */
	function refreshAjaxGrid(objGrid){
		
		removePredictiveItem();
		
		//get all grid filters
		var objFilters = objGrid.data("filters");
		
		if(!objFilters)
			return(false);
		
		if(objFilters.length == 0)
			return(false);
		
		var objAjaxOptions = getGridAjaxOptions(objFilters, objGrid);
		
		if(!objAjaxOptions)
			return(false);
		
		var ajaxUrl = objAjaxOptions["ajax_url"];
		
		if(g_vars.DEBUG_AJAX_OPTIONS == true){
			
			trace("DEBUG AJAX OPTIONS");
			trace(objAjaxOptions);
			return(false);
		}
		
		//do the ajax call
		showAjaxLoader(objGrid);
		showMultipleAjaxLoaders(objFilters, true);
		
		g_lastGridAjaxCall = objGrid;
						
		ajaxRequest(ajaxUrl,null,null, function(response){
			
			hideAjaxLoader(objGrid);
			showMultipleAjaxLoaders(objFilters, false);
			
			operateAjaxRefreshResponse(response, objGrid, objFilters);
			
		});
	}
	
	
	function ________RUN_______________(){}
	
	/**
	 * on filters change - refresh the page with the new query
	 */
	function onFiltersChange(objGrid){
		
		var query = buildUrlQuery();
				
		var url = getRedirectUrl(query);
		
		if(!url)
			throw new error("onFiltersChange error - empty redirect url");
				
		location.href = url;
	}
	
	
	/**
	 * get grid ajax options
	 */
	function getGridAjaxOptions(objFilters, objGrid, isPredictive){
		
		if(!objFilters)
			return(false);
		
		var urlAjax = g_urlBase;
		
		var strRefreshIDs = "";
		
		//get ajax options
		jQuery.each(objFilters, function(index, objFilter){
			
			var isPagination = isPaginationFilter(objFilter);
			
			if(isPagination == true){
				var urlPagination = getPaginationSelectedUrl(objFilter, isPredictive);
				
				if(isPredictive == true && !urlPagination)
					urlAjax = null;
				
				if(urlPagination)
					urlAjax = urlPagination;
			}
			
			//add widget id of the filter to refresh
			
			var filterWidgetID = getElementWidgetID(objFilter);
			
			if(strRefreshIDs)
				strRefreshIDs += ",";
			
			strRefreshIDs += filterWidgetID
		});
		
		if(urlAjax == null)
			return(null);
		
		var dataLayout = getElementLayoutData(objGrid);
		
		var widgetID = dataLayout["widgetid"];
		var layoutID = dataLayout["layoutid"];
		
		var urlAddition = "ucfrontajaxaction=getfiltersdata&layoutid="+layoutID+"&elid="+widgetID;
		
		urlAjax = addUrlParam(urlAjax, urlAddition);
		
		//add refresh ids
		if(strRefreshIDs)
			urlAjax += "&addelids="+strRefreshIDs;
		
		var output = {};
		output["ajax_url"] = urlAjax;
		
		return(output);
	}
	
	
	
	function ________INIT_______________(){}
	
		
	
	/**
	 * init events
	 */
	function initEvents(){
		
		var objCheckboxes = g_objFilters.filter("input[type=checkbox]");
		
		objCheckboxes.on("click", onFiltersChange);
		
	}
	
	
	/**
	 * init listing object
	 */
	function initGridObject(){
		
		//init the listing
		g_objGrid = jQuery("."+ g_vars.CLASS_GRID);
		
		if(g_objGrid.length == 0){
			g_objGrid = null;
			return(false);
		}
		
		//set only available grid
		if(g_objGrid.length > 1){
			g_objGrid = null;
		}
								
	}
	
	
	/**
	 * init grid filters
	 */
	function initGridFilters(){
		
		//init the filters objects
		g_objFilters = jQuery(".uc-grid-filter");
		
		if(g_objFilters.length == 0){
			return(false);
		}
		
	}
		
	
	/**
	 * init the globals
	 */
	function initGlobals(){
		
		if(typeof g_strFiltersData != "undefined"){
			g_filtersData = JSON.parse(g_strFiltersData);
		}
		
		if(jQuery.isEmptyObject(g_filtersData)){
			
			trace("filters error - filters data not found");
			return(false);
		}
		
		g_urlBase = getVal(g_filtersData, "urlbase");
		g_urlAjax = getVal(g_filtersData, "urlajax");
		
		if(!g_urlBase){
			trace("ue filters error - base url not inited");
			return(false);
		}

		if(!g_urlAjax){
			trace("ue filters error - ajax url not inited");
			return(false);
		}
		
		return(true);
	}
	
	
	
	/**
	 * init pagination filter
	 */
	function initPaginationFilter(objPagination){
		
		var objGrid = getClosestGrid(objPagination);
		
		if(!objGrid)
			return(null);
		
		var isAjax = objGrid.data("ajax");
		
		if(isAjax == false)
			return(false);
		
		//bind grid to pagination
		objPagination.data("grid", objGrid);
		
		//bind pagination to grid
		bindFilterToGrid(objGrid, objPagination);
		
	}
	
	
	/**
	 * init pagination filter
	 */
	function initPaginationFilters(){
		
		var objPaginations = jQuery(".uc-filter-pagination");
		
		if(objPaginations.length == 0)
			return(false);
		
		jQuery.each(objPaginations, function(index, pagination){
			
			var objPagination = jQuery(pagination);
			
			initPaginationFilter(objPagination);
			
		});
		
		//init the events
		var objParent = objPaginations.parents(".elementor");
		
		if(g_options.is_predictive_cache_enabled == true)
			objParent.on("predictive",".uc-filter-pagination a", onAjaxPaginationPredictive);
		
		objParent.on("click",".uc-filter-pagination a", onAjaxPaginationLinkClick);
		
	}
	
	
	
	/**
	 * init
	 */
	function init(){
				
		var success = initGlobals();
		
		if(success == false)
			return(false);
		
		//init the grid object
		initGridObject();
		
		//initGridFilters();
		
		initPaginationFilters();
		
		checkPredictiveRequest();
		
		//clearFilters(true);
		//initEvents();
		
	}
	
	
	/**
	 * init the class
	 */
	function construct(){
		
		if(!jQuery){
			trace("Filters not loaded, jQuery not loaded");
			return(false);
		}
		
		jQuery("document").ready(init);
		
	}
	
	construct();
}

new UEDynamicFilters();

