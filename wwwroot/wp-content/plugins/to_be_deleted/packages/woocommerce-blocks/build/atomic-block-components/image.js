(window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[]).push([[11],{346:function(e,t,c){"use strict";t.a={productLink:{type:"boolean",default:!0},showSaleBadge:{type:"boolean",default:!0},saleBadgeAlign:{type:"string",default:"right"},imageSizing:{type:"string",default:"full-size"},productId:{type:"number",default:0}}},347:function(e,t,c){"use strict";var n=c(11),r=c.n(n),a=c(4),o=c.n(a),l=c(13),i=c.n(l),u=c(0),s=(c(2),c(1)),d=c(7),p=c.n(d),b=c(5),m=c(43),g=c(75),f=c(72),O=c(262);function j(e,t){var c=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),c.push.apply(c,n)}return c}function w(e){for(var t=1;t<arguments.length;t++){var c=null!=arguments[t]?arguments[t]:{};t%2?j(Object(c),!0).forEach((function(t){o()(e,t,c[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(c)):j(Object(c)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(c,t))}))}return e}c(494);var h=function(){return Object(u.createElement)("img",{src:b.PLACEHOLDER_IMG_SRC,alt:"",width:500,height:500})},k=function(e){var t=e.image,c=e.onLoad,n=e.loaded,a=e.showFullSize,o=e.fallbackAlt,l=t||{},i=l.thumbnail,s=l.src,d=l.srcset,p=l.sizes,b=w({alt:l.alt||o,onLoad:c,hidden:!n,src:i},a&&{src:s,srcSet:d,sizes:p});return Object(u.createElement)(u.Fragment,null,b.src&&Object(u.createElement)("img",r()({"data-testid":"product-image"},b)),!n&&Object(u.createElement)(h,null))};t.a=Object(g.withProductDataContext)((function(e){var t=e.className,c=e.imageSizing,n=void 0===c?"full-size":c,r=e.productLink,a=void 0===r||r,l=e.showSaleBadge,d=e.saleBadgeAlign,b=void 0===d?"right":d,g=Object(m.useInnerBlockLayoutContext)().parentClassName,j=Object(m.useProductDataContext)().product,v=Object(u.useState)(!1),y=i()(v,2),E=y[0],S=y[1],P=Object(f.a)().dispatchStoreEvent;if(!j.id)return Object(u.createElement)("div",{className:p()(t,"wc-block-components-product-image","wc-block-components-product-image--placeholder",o()({},"".concat(g,"__product-image"),g))},Object(u.createElement)(h,null));var L=!!j.images.length,z=L?j.images[0]:null,_=a?"a":u.Fragment,B=Object(s.sprintf)(
/* translators: %s is referring to the product name */
Object(s.__)("Link to %s",'woocommerce'),j.name),C=w(w({href:j.permalink,rel:"nofollow"},!L&&{"aria-label":B}),{},{onClick:function(){P("product-view-link",{product:j})}});return Object(u.createElement)("div",{className:p()(t,"wc-block-components-product-image",o()({},"".concat(g,"__product-image"),g))},Object(u.createElement)(_,a&&C,!!l&&Object(u.createElement)(O.default,{align:b,product:j}),Object(u.createElement)(k,{fallbackAlt:j.name,image:z,onLoad:function(){return S(!0)},loaded:E,showFullSize:"cropped"!==n})))}))},494:function(e,t){},821:function(e,t,c){"use strict";c.r(t);var n=c(820),r=c(347),a=c(346);t.default=Object(n.a)(a.a)(r.a)}}]);