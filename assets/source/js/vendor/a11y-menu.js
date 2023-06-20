'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Navigation = function () {
    function Navigation() {
        var _ref = arguments.length <= 0 || arguments[0] === undefined ? {} : arguments[0];

        var _ref$menuId = _ref.menuId;
        var menuId = _ref$menuId === undefined ? 'am-main-menu' : _ref$menuId;
        var _ref$click = _ref.click;
        var click = _ref$click === undefined ? false : _ref$click;

        _classCallCheck(this, Navigation);

        this.menu = null;
        this.menuId = menuId;
        this.click = click;
        this.currentItem = null;
    }

    /**
     * 
     * js is available
     * remove the no-js class from nav menu list items
     * 
     */


    _createClass(Navigation, [{
        key: 'removeNoJs',
        value: function removeNoJs() {
            var listItems = Array.from(this.menu.querySelectorAll('.no-js'));
            listItems.map(function (item) {
                return item.classList.remove('no-js');
            });
        }

        /**
         * 
         * Get the button element which is expanded
         * Helps with identifying the top level list item
         * 
         * @return DOM element
         */

    }, {
        key: 'getCurrentTopLevelItem',
        value: function getCurrentTopLevelItem(target) {
            if (target !== null) {
                return target.closest('#' + this.menuId + ' > li');
            }
        }

        /**
         *
         * Manage the state of the top level item associated with targets
         * 
         * @param {*} target 
         * @returns {Element} the top level <li> associated with the target
         * @memberof Navigation
         */

    }, {
        key: 'toggleCurrentTopLevelItemClass',
        value: function toggleCurrentTopLevelItemClass(target) {
            var topLevelItems = Array.from(document.querySelectorAll('#' + this.menuId + ' > li'));
            return topLevelItems.map(function (item) {
                item.classList.remove('am-current-item');
                if (item.contains(target)) {
                    item.classList.add('am-current-item');
                    return item;
                }
            }).filter(function (item) {
                if (item !== undefined) {
                    return item;
                }
            })[0];
        }

        /**
         * 
         * Opens and closes submenus
         * Change the state of the aria-expanded property and submenu class
         *
         * @param {*} target DOM Node - specifically a <button />
         * @memberof Navigation
         */

    }, {
        key: 'manageSubmenuState',
        value: function manageSubmenuState(target) {
            var buttons = Array.from(this.menu.querySelectorAll('.am-submenu-toggle'));

            buttons.map(function (button) {
                var prevButton = button.parentElement.parentElement.previousElementSibling;
                var submenu = button.nextElementSibling;
                var submenuOpenClass = 'am-submenu-list-open';
                var sameNode = button.isSameNode(target);
                var ariaExpanded = button.getAttribute('aria-expanded');
                var parentSubmenu = void 0;

                // if for some reason there's a button with no submenu, return immediately
                if (!submenu) return;

                // case - clicking on a sub-submenu button which is currently NOT expanded.
                if (sameNode && ariaExpanded === 'false' && prevButton) {

                    // find the parent submenu
                    parentSubmenu = prevButton.nextElementSibling;

                    // toggle the states of the previous button and the button/target
                    prevButton.setAttribute('aria-expanded', 'true');
                    button.setAttribute('aria-expanded', 'true');

                    // keep the parent submenu open
                    parentSubmenu.classList.add(submenuOpenClass);

                    // open the sub-submenu
                    submenu.classList.add(submenuOpenClass);
                }

                // case - clicking on a sub-submenu button which is currently expanded.
                else if (sameNode && ariaExpanded === 'true' && prevButton) {

                        // find the parent submenu
                        parentSubmenu = prevButton.nextElementSibling;

                        // keep the previous button expanded and toggle the button/target
                        prevButton.setAttribute('aria-expanded', 'true');
                        button.setAttribute('aria-expanded', 'false');

                        // keep the parent submenu open
                        parentSubmenu.classList.add(submenuOpenClass);

                        // close the sub-submenu
                        submenu.classList.remove(submenuOpenClass);
                    }
                    // case - clicking on a top level button which is currently NOT expanded
                    else if (sameNode && ariaExpanded === 'false') {
                            // expand the button
                            button.setAttribute('aria-expanded', 'true');
                            // open the submenu
                            submenu.classList.add(submenuOpenClass);
                        }
                        // case - all other buttons
                        else {
                                // reset aria-expanded to false
                                button.setAttribute('aria-expanded', 'false');
                                // close the submenu
                                submenu.classList.remove(submenuOpenClass);
                            }
            });
        }

        /**
         *
         * remove the am-submenu-list-open class from all submenus not associated with the target
         * 
         * @param {object} target - the event target
         * @memberof Navigation
         */

    }, {
        key: 'clearSubmenuClass',
        value: function clearSubmenuClass(target) {
            var menuArray = Array.from(document.querySelectorAll('.am-submenu-list-open'));
            if (!target.closest('.am-submenu-toggle')) {
                menuArray.map(function (menu) {
                    return menu.classList.remove('am-submenu-list-open');
                });
            }
        }

        /**
         *
         * set aria-expanded false on all buttons not associated with the target
         *
         * @param {object} target - the event target
         * @memberof Navigation
         */

    }, {
        key: 'clearAllAriaExpanded',
        value: function clearAllAriaExpanded(target) {
            var buttonArray = Array.from(document.querySelectorAll('.am-submenu-toggle'));
            if (!target.closest('.am-submenu-toggle')) {
                buttonArray.map(function (button) {
                    return button.setAttribute('aria-expanded', 'false');
                });
            }
        }

        /**
         *
         * close all submenus and set the state of all items with aria-expanded to false
         * remove event listeners from the document
         *
         * @param {object} { target } destructured from the event object
         * @memberof Navigation
         */

    }, {
        key: 'clearAll',
        value: function clearAll(_ref2) {
            var target = _ref2.target;

            this.clearSubmenuClass(target);
            this.clearAllAriaExpanded(target);
            document.removeEventListener('click', this.clearAll.bind(this));
            //document.removeEventListener('focusin', this.clearAll.bind(this))
            document.removeEventListener('keydown', this.clearAll.bind(this));
        }

        /**
         *
         * Remove the no-js class and attach event listeners to the menu
         * 
         * @memberof Navigation
         */

    }, {
        key: 'setMenuEventListeners',
        value: function setMenuEventListeners() {
            var _this = this;

            //let listeners = ['focusin', 'keydown'];
            var listeners = ['keydown'];

            if (this.click) {
                listeners.push('click');

                var subMenuList = [].slice.call(this.menu.querySelectorAll('.am-submenu-list'));

                subMenuList.forEach(function (menu) {
                    return menu.classList.add('am-click-menu');
                });
            }

            for (var i = 0; i < listeners.length; i++) {
                this.menu.addEventListener(listeners[i], function (evt) {
                    _this.eventDispatcher(evt);
                });
            }
        }

        /**
         *
         * attach event listeners to the document
         *  - click: clicks on the body clear the menu
         *  - focusin: if the body gets focus, clear the menu
         *  - keydown: if the escape key is pressed, clear the menu
         *
         * @param {object} target
         * @memberof Navigation
         */

    }, {
        key: 'setDocumentEventListeners',
        value: function setDocumentEventListeners(target) {
            var _this2 = this;

            if (target.getAttribute('aria-expanded') === 'true') {
                this.clearAll = this.clearAll.bind(this);

                document.addEventListener('click', this.clearAll);

                /*
                         document.addEventListener('focusin', (evt) => {
                             if (!this.menu.contains(evt.target)) {
                                 this.clearAll({ target: document.body })
                             }
                         })
                */

                document.addEventListener('keydown', function (evt) {
                    if (evt.which === 27) {
                        _this2.clearAll({ target: document.body });
                    }
                });
            }
        }

        /**
         *
         * dispatch events to the correct functions.
         * types include: focusin, keydown, mousedown
         * 
         * treat keydowns from the return key (13) as click events
         *
         * @param {object} evt
         * @returns void
         * @memberof Navigation
         */

    }, {
        key: 'eventDispatcher',
        value: function eventDispatcher(evt) {
            switch (evt.type) {
                /*
                         case 'focusin':
                             this.focusInHandler(evt)
                             break;
                */
                case 'click':
                    this.clickHandler(evt);
                    break;
                default:
                    return;
            }
        }

        /**
         *
         * handle mousedown events by managing
         * - submenu classes
         * - aria-expanded state
         * - event listeners on the document
         * 
         * @param {object} { target } destructured from the event object
         * @memberof Navigation
         */

    }, {
        key: 'clickHandler',
        value: function clickHandler(_ref3) {
            var target = _ref3.target;

            if (target.localName !== 'button') return;
            this.toggleCurrentTopLevelItemClass(target);
            this.manageSubmenuState(target);
            this.setDocumentEventListeners(target);
        }

        /**
         *
         * Handle focusin events
         * 
         * @param {*} { target, relatedTarget } DOM targets 
         * @memberof Navigation
         */
        /*
           focusInHandler({ target, relatedTarget }) {
               const topItem = this.toggleCurrentTopLevelItemClass(target)
               if (this.menu.contains(relatedTarget) && !topItem.contains(relatedTarget)) {
                   this.clearAll({ target: document.body })
               }
           }
        */

    }, {
        key: 'init',
        value: function init() {
            this.menu = document.getElementById(this.menuId);
            this.removeNoJs();
            this.setMenuEventListeners();
        }
    }]);

    return Navigation;
}();

var menuOpts = {
    // assumes a <ul> with an id of 'primary-menu'
    menuId: 'primary-menu',
    click: true
};

var navigation = new Navigation(menuOpts);

document.addEventListener('DOMContentLoaded', function () {
    navigation.init();
});
