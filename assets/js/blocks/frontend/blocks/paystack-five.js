(()=>{"use strict";var e={20:(e,t,r)=>{var o=r(609),s=Symbol.for("react.element"),i=Symbol.for("react.fragment"),a=Object.prototype.hasOwnProperty,l=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,n={key:!0,ref:!0,__self:!0,__source:!0};function c(e,t,r){var o,i={},c=null,d=null;for(o in void 0!==r&&(c=""+r),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(d=t.ref),t)a.call(t,o)&&!n.hasOwnProperty(o)&&(i[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===i[o]&&(i[o]=t[o]);return{$$typeof:s,type:e,key:c,ref:d,props:i,_owner:l.current}}t.Fragment=i,t.jsx=c,t.jsxs=c},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},t={};const r=window.wc.wcBlocksRegistry,o=window.wc.wcSettings,s=window.wp.i18n,i=window.wp.htmlEntities;var a=function r(o){var s=t[o];if(void 0!==s)return s.exports;var i=t[o]={exports:{}};return e[o](i,i.exports,r),i.exports}(848);const l=(0,s.__)("Scapa ","woo-scapa"),n=({title:e})=>(0,i.decodeEntities)(e)||l,c=({description:e})=>(0,i.decodeEntities)(e||""),d=({logoUrls:e,label:t})=>(0,a.jsx)("div",{style:{display:"flex",flexDirection:"row",gap:"0.5rem",flexWrap:"wrap"},children:e.map(((e,r)=>(0,a.jsx)("img",{src:e,alt:t},r)))}),p=({logoUrls:e,title:t})=>(0,a.jsx)(a.Fragment,{children:(0,a.jsxs)("div",{style:{display:"flex",flexDirection:"row",gap:"0.5rem"},children:[(0,a.jsx)("div",{children:n({title:t})}),(0,a.jsx)(d,{logoUrls:e,label:n({title:t})})]})}),w=(0,o.getSetting)("scapa-five_data",{}),f=n({title:w.title}),_={name:"scapa-five",label:(0,a.jsx)(p,{logoUrls:w.logo_urls,title:w.title}),content:(0,a.jsx)(c,{description:w.description}),edit:(0,a.jsx)(c,{description:w.description}),canMakePayment:()=>!0,ariaLabel:f,supports:{showSavedCards:w.allow_saved_cards,showSaveOption:w.allow_saved_cards,features:w.supports}};(0,r.registerPaymentMethod)(_)})();