// Replacing Add To Cart Button
function update_addToCart_src(ELE) {
	var addToCart_ele=document.createElement("div");
		addToCart_ele.setAttribute("onclick", ELE.getAttribute("onclick"));
		addToCart_ele.className="fsButton cartButton addToCart";
		addToCart_ele.innerHTML='<span class="fs_icon shoppingCart"></span>';
			addToCart_ele.innerHTML+='<span class="words">Add To Cart</span>';
	
	ELE.parentNode.insertBefore(addToCart_ele, ELE.nextSibling);
	ELE.style.display="none";
	
}

if (ifQselector) {
	var addToCart_query=document.querySelectorAll("*[alt='Add to Cart']");
	
	for (I=0; I<addToCart_query.length; I++) {
		update_addToCart_src(addToCart_query[I]);
	}
}
else {
	var addToCart_queries=new Array(document.getElementsByTagName("input"), document.getElementsByTagName("img"));
	
	for (Q=0; Q<addToCart_queries.length; Q++) {
		for (I=0; I<addToCart_queries[Q].length; I++) {
			var INPUT=addToCart_queries[Q][I];
			
			if (INPUT.hasAttribute("src") && INPUT.hasAttribute("alt") && INPUT.getAttribute("alt")=="Add to Cart") {
				update_addToCart_src(INPUT);
			}
		}
	}

}
