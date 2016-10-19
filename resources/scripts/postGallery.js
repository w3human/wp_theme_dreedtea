var postGallery_cItem; // image->ID
var postGallery_cItem_order; 

var postGalleryPopup_api={
	"ele": {
		"main": document.getElementById("postGallery_popup"),
		
		"theCaption": document.getElementById("pg_caption"),
		"theImage": document.getElementById("pg_theImage"),
		
		"buttons": {
			"next": document.getElementById("pg_nextItem"),
			"back": document.getElementById("pg_backItem"),
		},
	},
	
	"setdata": function() {
		postGalleryPopup_api["ele"].theImage.src="";
		
		for(o=0; o<postGallery_items_order.length; o++) {
			if (postGallery_items_order[o]==Number(postGallery_cItem)) {
				postGallery_cItem_order=o;
			}
		}
		
		var itemData=postGallery_items[""+ postGallery_cItem +""];
		
		postGalleryPopup_api["ele"].theCaption.innerHTML=decodeURIComponent(itemData["caption"] || itemData["description"]);
		
		postGalleryPopup_api["ele"].theImage.src=itemData["image"].full;
			var imageData=new Image();
				imageData.src=itemData["image"].full;

			imageData.onload=function() {
				addSheet("#THE_postGallery_popup{width: "+ imageData.naturalWidth +"px; min-height: "+ imageData.naturalHeight +"px;}");
				
				popupStyler("postGallery_popup"); 		
			};
	},
	
	"open": function() {
		postGalleryPopup_api.setdata();
		popupStyler("postGallery_popup"); 
	},
	
	"nextItem": function() {
		eval("postGallery_cItem_order"+ ((postGallery_cItem_order==(postGallery_items_order.length-1))?"=0":"+=1"));
				
		postGallery_cItem=postGallery_items_order[(postGallery_cItem_order)];
		postGalleryPopup_api.setdata();
	},

	"backItem": function() {
		eval("postGallery_cItem_order"+ ((postGallery_cItem_order==0)?"="+(postGallery_items_order.length-1):"-=1"));
		
		postGallery_cItem=postGallery_items_order[(postGallery_cItem_order)];
		postGalleryPopup_api.setdata();
	},
};

var postGallery_itemEles=document.getElementsByName("postGallery_item");

for (I=0; I<postGallery_itemEles.length; I++) {
	var an_item=postGallery_itemEles[I];
	
	an_item.onclick=function() {
		postGallery_cItem=this.id;
		postGalleryPopup_api.open();
	};
}

postGalleryPopup_api["ele"]["buttons"]["back"].onclick=postGalleryPopup_api.backItem;
postGalleryPopup_api["ele"]["buttons"]["next"].onclick=postGalleryPopup_api.nextItem;

add_keydown(37, function() {
	if (hClass(postGalleryPopup_api["ele"]["main"], "active")) {
		postGalleryPopup_api.backItem();
	}
});

add_keydown(39, function() {
	if (hClass(postGalleryPopup_api["ele"]["main"], "active")) {
		postGalleryPopup_api.nextItem();
	}
});

