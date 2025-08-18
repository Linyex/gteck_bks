/**
 * Modern Component Library JavaScript
 * Версия: 2.0.0
 * Автор: Frontend Developer
 * 
 * Особенности:
 * - Компонентный подход
 * - Современные анимации
 * - Accessibility (a11y)
 * - Performance optimized
 * - Event delegation
 * - Modular architecture
 */

(function() {
  'use strict';

  // ===== UTILITY FUNCTIONS =====
  const Utils = {
    // DOM helpers
    $: (selector) => document.querySelector(selector),
    $$: (selector) => document.querySelectorAll(selector),
    
    // Event helpers
    on: (element, event, handler, options = {}) => {
      element.addEventListener(event, handler, options);
    },
    
    off: (element, event, handler) => {
      element.removeEventListener(event, handler);
    },
    
    // Animation helpers
    animate: (element, keyframes, options = {}) => {
      return element.animate(keyframes, {
        duration: 300,
        easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
        ...options
      });
    },
    
    // Accessibility helpers
    trapFocus: (element) => {
      const focusableElements = element.querySelectorAll(
        'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
      );
      const firstFocusable = focusableElements[0];
      const lastFocusable = focusableElements[focusableElements.length - 1];
      
      return {
        first: firstFocusable,
        last: lastFocusable,
        elements: focusableElements
      };
    },
    
    // Performance helpers
    debounce: (func, wait) => {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    },
    
    throttle: (func, limit) => {
      let inThrottle;
      return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
          func.apply(context, args);
          inThrottle = true;
          setTimeout(() => inThrottle = false, limit);
        }
      };
    }
  };

  // ===== BASE COMPONENT CLASS =====
  class Component {
    constructor(element, options = {}) {
      this.element = element;
      this.options = { ...this.defaults, ...options };
      this.init();
    }
    
    get defaults() {
      return {};
    }
    
    init() {
      // Override in subclasses
    }
    
    destroy() {
      // Override in subclasses
    }
  }

  // ===== MODAL COMPONENT =====
  class Modal extends Component {
    get defaults() {
      return {
        backdrop: true,
        keyboard: true,
        focus: true,
        show: false
      };
    }
    
    init() {
      this.modal = this.element;
      this.dialog = this.modal.querySelector('.modal-dialog');
      this.backdrop = null;
      this.focusTrap = null;
      
      this.bindEvents();
      if (this.options.show) {
        this.show();
      }
    }
    
    bindEvents() {
      // Close button
      const closeBtn = this.modal.querySelector('[data-dismiss="modal"]');
      if (closeBtn) {
        Utils.on(closeBtn, 'click', () => this.hide());
      }
      
      // Backdrop click
      if (this.options.backdrop) {
        Utils.on(this.modal, 'click', (e) => {
          if (e.target === this.modal) {
            this.hide();
          }
        });
      }
      
      // Keyboard events
      if (this.options.keyboard) {
        Utils.on(document, 'keydown', (e) => {
          if (e.key === 'Escape' && this.isVisible()) {
            this.hide();
          }
        });
      }
    }
    
    show() {
      if (this.isVisible()) return;
      
      // Create backdrop
      if (this.options.backdrop) {
        this.createBackdrop();
      }
      
      // Show modal
      this.modal.classList.add('show');
      this.modal.style.display = 'block';
      
      // Focus management
      if (this.options.focus) {
        this.focusTrap = Utils.trapFocus(this.modal);
        this.focusTrap.first?.focus();
      }
      
      // Animate in
      Utils.animate(this.dialog, [
        { opacity: 0, transform: 'scale(0.8) translateY(-20px)' },
        { opacity: 1, transform: 'scale(1) translateY(0)' }
      ], { duration: 300 });
      
      // Trigger event
      this.modal.dispatchEvent(new CustomEvent('modal:shown'));
    }
    
    hide() {
      if (!this.isVisible()) return;
      
      // Animate out
      const animation = Utils.animate(this.dialog, [
        { opacity: 1, transform: 'scale(1) translateY(0)' },
        { opacity: 0, transform: 'scale(0.8) translateY(-20px)' }
      ], { duration: 200 });
      
      animation.onfinish = () => {
        this.modal.classList.remove('show');
        this.modal.style.display = 'none';
        
        // Remove backdrop
        if (this.backdrop) {
          this.backdrop.remove();
          this.backdrop = null;
        }
        
        // Trigger event
        this.modal.dispatchEvent(new CustomEvent('modal:hidden'));
      };
    }
    
    createBackdrop() {
      this.backdrop = document.createElement('div');
      this.backdrop.className = 'modal-backdrop';
      document.body.appendChild(this.backdrop);
      
      Utils.animate(this.backdrop, [
        { opacity: 0 },
        { opacity: 1 }
      ], { duration: 200 });
    }
    
    isVisible() {
      return this.modal.classList.contains('show');
    }
    
    destroy() {
      this.hide();
      // Remove event listeners
    }
  }

  // ===== DROPDOWN COMPONENT =====
  class Dropdown extends Component {
    get defaults() {
      return {
        autoClose: true,
        boundary: 'viewport'
      };
    }
    
    init() {
      this.trigger = this.element;
      this.menu = this.trigger.nextElementSibling;
      this.isOpen = false;
      
      this.bindEvents();
    }
    
    bindEvents() {
      Utils.on(this.trigger, 'click', (e) => {
        e.preventDefault();
        this.toggle();
      });
      
      // Close on outside click
      if (this.options.autoClose) {
        Utils.on(document, 'click', (e) => {
          if (!this.trigger.contains(e.target) && !this.menu.contains(e.target)) {
            this.hide();
          }
        });
      }
      
      // Keyboard navigation
      Utils.on(this.trigger, 'keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.toggle();
        }
      });
    }
    
    show() {
      if (this.isOpen) return;
      
      this.isOpen = true;
      this.menu.classList.add('show');
      this.trigger.setAttribute('aria-expanded', 'true');
      
      // Position menu
      this.positionMenu();
      
      // Animate in
      Utils.animate(this.menu, [
        { opacity: 0, transform: 'translateY(-10px)' },
        { opacity: 1, transform: 'translateY(0)' }
      ], { duration: 200 });
    }
    
    hide() {
      if (!this.isOpen) return;
      
      this.isOpen = false;
      this.menu.classList.remove('show');
      this.trigger.setAttribute('aria-expanded', 'false');
    }
    
    toggle() {
      this.isOpen ? this.hide() : this.show();
    }
    
    positionMenu() {
      const rect = this.trigger.getBoundingClientRect();
      const menuRect = this.menu.getBoundingClientRect();
      
      // Check if menu fits below
      if (rect.bottom + menuRect.height > window.innerHeight) {
        this.menu.style.top = 'auto';
        this.menu.style.bottom = '100%';
      } else {
        this.menu.style.top = '100%';
        this.menu.style.bottom = 'auto';
      }
    }
  }

  // ===== TOOLTIP COMPONENT =====
  class Tooltip extends Component {
    get defaults() {
      return {
        placement: 'top',
        trigger: 'hover',
        delay: 0,
        html: false
      };
    }
    
    init() {
      this.trigger = this.element;
      this.tooltip = null;
      this.timeout = null;
      
      this.bindEvents();
    }
    
    bindEvents() {
      if (this.options.trigger === 'hover') {
        Utils.on(this.trigger, 'mouseenter', () => this.show());
        Utils.on(this.trigger, 'mouseleave', () => this.hide());
      } else if (this.options.trigger === 'click') {
        Utils.on(this.trigger, 'click', () => this.toggle());
      }
      
      Utils.on(this.trigger, 'focus', () => this.show());
      Utils.on(this.trigger, 'blur', () => this.hide());
    }
    
    show() {
      if (this.tooltip) return;
      
      const text = this.trigger.getAttribute('data-tooltip') || this.trigger.title;
      if (!text) return;
      
      this.createTooltip(text);
      this.positionTooltip();
      
      // Animate in
      Utils.animate(this.tooltip, [
        { opacity: 0, transform: 'scale(0.8)' },
        { opacity: 1, transform: 'scale(1)' }
      ], { duration: 200 });
    }
    
    hide() {
      if (!this.tooltip) return;
      
      Utils.animate(this.tooltip, [
        { opacity: 1, transform: 'scale(1)' },
        { opacity: 0, transform: 'scale(0.8)' }
      ], { duration: 150 }).onfinish = () => {
        this.tooltip.remove();
        this.tooltip = null;
      };
    }
    
    toggle() {
      this.tooltip ? this.hide() : this.show();
    }
    
    createTooltip(text) {
      this.tooltip = document.createElement('div');
      this.tooltip.className = `tooltip tooltip-${this.options.placement}`;
      this.tooltip.setAttribute('role', 'tooltip');
      this.tooltip.innerHTML = text;
      
      document.body.appendChild(this.tooltip);
    }
    
    positionTooltip() {
      const rect = this.trigger.getBoundingClientRect();
      const tooltipRect = this.tooltip.getBoundingClientRect();
      
      let top, left;
      
      switch (this.options.placement) {
        case 'top':
          top = rect.top - tooltipRect.height - 8;
          left = rect.left + (rect.width - tooltipRect.width) / 2;
          break;
        case 'bottom':
          top = rect.bottom + 8;
          left = rect.left + (rect.width - tooltipRect.width) / 2;
          break;
        case 'left':
          top = rect.top + (rect.height - tooltipRect.height) / 2;
          left = rect.left - tooltipRect.width - 8;
          break;
        case 'right':
          top = rect.top + (rect.height - tooltipRect.height) / 2;
          left = rect.right + 8;
          break;
      }
      
      this.tooltip.style.top = `${top}px`;
      this.tooltip.style.left = `${left}px`;
    }
  }

  // ===== COLLAPSE COMPONENT =====
  class Collapse extends Component {
    get defaults() {
      return {
        accordion: false
      };
    }
    
    init() {
      this.trigger = this.element;
      this.content = document.querySelector(this.trigger.getAttribute('data-target'));
      this.isOpen = false;
      
      this.bindEvents();
    }
    
    bindEvents() {
      Utils.on(this.trigger, 'click', (e) => {
        e.preventDefault();
        this.toggle();
      });
    }
    
    show() {
      if (this.isOpen) return;
      
      // Close other accordion items
      if (this.options.accordion) {
        const openItems = document.querySelectorAll('.collapse.show');
        openItems.forEach(item => {
          if (item !== this.content) {
            new Collapse(item.previousElementSibling).hide();
          }
        });
      }
      
      this.isOpen = true;
      this.content.classList.add('show');
      this.trigger.setAttribute('aria-expanded', 'true');
      
      // Animate height
      const height = this.content.scrollHeight;
      this.content.style.height = '0px';
      this.content.style.overflow = 'hidden';
      
      requestAnimationFrame(() => {
        this.content.style.height = `${height}px`;
        
        setTimeout(() => {
          this.content.style.height = '';
          this.content.style.overflow = '';
        }, 300);
      });
    }
    
    hide() {
      if (!this.isOpen) return;
      
      this.isOpen = false;
      this.content.classList.remove('show');
      this.trigger.setAttribute('aria-expanded', 'false');
      
      // Animate height
      const height = this.content.scrollHeight;
      this.content.style.height = `${height}px`;
      this.content.style.overflow = 'hidden';
      
      requestAnimationFrame(() => {
        this.content.style.height = '0px';
        
        setTimeout(() => {
          this.content.style.height = '';
          this.content.style.overflow = '';
        }, 300);
      });
    }
    
    toggle() {
      this.isOpen ? this.hide() : this.show();
    }
  }

  // ===== LAZY LOADING COMPONENT =====
  class LazyLoader extends Component {
    get defaults() {
      return {
        threshold: 0.1,
        rootMargin: '50px'
      };
    }
    
    init() {
      this.images = Utils.$$('[data-src]');
      this.observer = null;
      
      this.initObserver();
    }
    
    initObserver() {
      if ('IntersectionObserver' in window) {
        this.observer = new IntersectionObserver(
          (entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                this.loadImage(entry.target);
                this.observer.unobserve(entry.target);
              }
            });
          },
          {
            threshold: this.options.threshold,
            rootMargin: this.options.rootMargin
          }
        );
        
        this.images.forEach(img => this.observer.observe(img));
      } else {
        // Fallback for older browsers
        this.loadAllImages();
      }
    }
    
    loadImage(img) {
      const src = img.getAttribute('data-src');
      if (!src) return;
      
      img.src = src;
      img.removeAttribute('data-src');
      
      // Add fade-in animation
      Utils.animate(img, [
        { opacity: 0 },
        { opacity: 1 }
      ], { duration: 300 });
    }
    
    loadAllImages() {
      this.images.forEach(img => this.loadImage(img));
    }
    
    destroy() {
      if (this.observer) {
        this.observer.disconnect();
      }
    }
  }

  // ===== SCROLL ANIMATIONS =====
  class ScrollAnimations extends Component {
    get defaults() {
      return {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };
    }
    
    init() {
      this.elements = Utils.$$('[data-animate]');
      this.observer = null;
      
      this.initObserver();
    }
    
    initObserver() {
      if ('IntersectionObserver' in window) {
        this.observer = new IntersectionObserver(
          (entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                this.animateElement(entry.target);
                this.observer.unobserve(entry.target);
              }
            });
          },
          {
            threshold: this.options.threshold,
            rootMargin: this.options.rootMargin
          }
        );
        
        this.elements.forEach(el => this.observer.observe(el));
      }
    }
    
    animateElement(element) {
      const animation = element.getAttribute('data-animate');
      const delay = element.getAttribute('data-delay') || 0;
      
      setTimeout(() => {
        element.classList.add('animate', animation);
      }, delay);
    }
    
    destroy() {
      if (this.observer) {
        this.observer.disconnect();
      }
    }
  }

  // ===== FORM VALIDATION =====
  class FormValidator extends Component {
    get defaults() {
      return {
        errorClass: 'is-invalid',
        successClass: 'is-valid'
      };
    }
    
    init() {
      this.form = this.element;
      this.fields = this.form.querySelectorAll('input, textarea, select');
      
      this.bindEvents();
    }
    
    bindEvents() {
      Utils.on(this.form, 'submit', (e) => {
        if (!this.validateForm()) {
          e.preventDefault();
        }
      });
      
      this.fields.forEach(field => {
        Utils.on(field, 'blur', () => this.validateField(field));
        Utils.on(field, 'input', Utils.debounce(() => this.validateField(field), 300));
      });
    }
    
    validateField(field) {
      const value = field.value.trim();
      const rules = field.getAttribute('data-validate');
      
      if (!rules) return true;
      
      const ruleList = rules.split('|');
      let isValid = true;
      let errorMessage = '';
      
      ruleList.forEach(rule => {
        const [ruleName, ruleValue] = rule.split(':');
        
        switch (ruleName) {
          case 'required':
            if (!value) {
              isValid = false;
              errorMessage = 'Это поле обязательно для заполнения';
            }
            break;
          case 'email':
            if (value && !this.isValidEmail(value)) {
              isValid = false;
              errorMessage = 'Введите корректный email';
            }
            break;
          case 'min':
            if (value && value.length < parseInt(ruleValue)) {
              isValid = false;
              errorMessage = `Минимальная длина ${ruleValue} символов`;
            }
            break;
          case 'max':
            if (value && value.length > parseInt(ruleValue)) {
              isValid = false;
              errorMessage = `Максимальная длина ${ruleValue} символов`;
            }
            break;
        }
      });
      
      this.setFieldState(field, isValid, errorMessage);
      return isValid;
    }
    
    validateForm() {
      let isValid = true;
      
      this.fields.forEach(field => {
        if (!this.validateField(field)) {
          isValid = false;
        }
      });
      
      return isValid;
    }
    
    setFieldState(field, isValid, message) {
      field.classList.remove(this.options.errorClass, this.options.successClass);
      field.classList.add(isValid ? this.options.successClass : this.options.errorClass);
      
      // Remove existing error message
      const existingError = field.parentNode.querySelector('.invalid-feedback');
      if (existingError) {
        existingError.remove();
      }
      
      // Add error message
      if (!isValid && message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
      }
    }
    
    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }
  }

  // ===== AUTO-INITIALIZATION =====
  class ComponentManager {
    constructor() {
      this.components = new Map();
      this.init();
    }
    
    init() {
      // Initialize modals
      Utils.$$('[data-toggle="modal"]').forEach(modal => {
        this.components.set(modal, new Modal(modal));
      });
      
      // Initialize dropdowns
      Utils.$$('[data-toggle="dropdown"]').forEach(dropdown => {
        this.components.set(dropdown, new Dropdown(dropdown));
      });
      
      // Initialize tooltips
      Utils.$$('[data-tooltip]').forEach(tooltip => {
        this.components.set(tooltip, new Tooltip(tooltip));
      });
      
      // Initialize collapses
      Utils.$$('[data-toggle="collapse"]').forEach(collapse => {
        this.components.set(collapse, new Collapse(collapse));
      });
      
      // Initialize lazy loading
      if (Utils.$$('[data-src]').length > 0) {
        this.components.set('lazyLoader', new LazyLoader());
      }
      
      // Initialize scroll animations
      if (Utils.$$('[data-animate]').length > 0) {
        this.components.set('scrollAnimations', new ScrollAnimations());
      }
      
      // Initialize form validation
      Utils.$$('form[data-validate]').forEach(form => {
        this.components.set(form, new FormValidator(form));
      });
    }
    
    destroy() {
      this.components.forEach(component => {
        if (component.destroy) {
          component.destroy();
        }
      });
      this.components.clear();
    }
  }

  // ===== GLOBAL EXPORTS =====
  window.GTECComponents = {
    Component,
    Modal,
    Dropdown,
    Tooltip,
    Collapse,
    LazyLoader,
    ScrollAnimations,
    FormValidator,
    ComponentManager,
    Utils
  };

  // Auto-initialize when DOM is ready
  if (document.readyState === 'loading') {
    Utils.on(document, 'DOMContentLoaded', () => {
      window.componentManager = new ComponentManager();
      try { applyContentOverrides(); } catch(e) {}
      try {
        // Безопасный детектор эмодзи: охватывает основные диапазоны Юникода
        const emojiRegex = /[\u{1F1E6}-\u{1F1FF}\u{1F200}-\u{1F251}\u{1F300}-\u{1F6FF}\u{1F680}-\u{1F6FF}\u{1F900}-\u{1F9FF}\u{1FA70}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/u;
        const titles = document.querySelectorAll('.site-public .section-title, .site-public h2.section-title, .site-public h3.section-title');
        const wrapNodeEmojis = (textNode) => {
          const text = textNode.nodeValue;
          const frag = document.createDocumentFragment();
          for (const ch of text) {
            if (emojiRegex.test(ch)) {
              const span = document.createElement('span');
              span.className = 'title-emoji';
              span.textContent = ch;
              frag.appendChild(span);
            } else {
              frag.appendChild(document.createTextNode(ch));
            }
          }
          textNode.parentNode.replaceChild(frag, textNode);
        };
        titles.forEach((title) => {
          if (title.dataset.emojiWrapped === '1' || title.querySelector('span.title-emoji')) return;
          const walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, null);
          const nodesToProcess = [];
          let node;
          while ((node = walker.nextNode())) {
            if (node.nodeValue && emojiRegex.test(node.nodeValue)) nodesToProcess.push(node);
          }
          nodesToProcess.forEach(wrapNodeEmojis);
          title.dataset.emojiWrapped = '1';
        });
      } catch (_) {}
    });
  } else {
    window.componentManager = new ComponentManager();
    try { applyContentOverrides(); } catch(e) {}
    try {
      const emojiRegex = /[\u{1F1E6}-\u{1F1FF}\u{1F200}-\u{1F251}\u{1F300}-\u{1F6FF}\u{1F680}-\u{1F6FF}\u{1F900}-\u{1F9FF}\u{1FA70}-\u{1FAFF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/u;
      const titles = document.querySelectorAll('.site-public .section-title, .site-public h2.section-title, .site-public h3.section-title');
      const wrapNodeEmojis = (textNode) => {
        const text = textNode.nodeValue;
        const frag = document.createDocumentFragment();
        for (const ch of text) {
          if (emojiRegex.test(ch)) {
            const span = document.createElement('span');
            span.className = 'title-emoji';
            span.textContent = ch;
            frag.appendChild(span);
          } else {
            frag.appendChild(document.createTextNode(ch));
          }
        }
        textNode.parentNode.replaceChild(frag, textNode);
      };
      titles.forEach((title) => {
        if (title.dataset.emojiWrapped === '1' || title.querySelector('span.title-emoji')) return;
        const walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, null);
        const nodesToProcess = [];
        let node;
        while ((node = walker.nextNode())) {
          if (node.nodeValue && emojiRegex.test(node.nodeValue)) nodesToProcess.push(node);
        }
        nodesToProcess.forEach(wrapNodeEmojis);
        title.dataset.emojiWrapped = '1';
      });
    } catch (_) {}
  }

  // Применение правок контента из БД (с пере-применением)
  let __gtecOverrides = null;
  function applyOverridesToDom(list) {
    if (!Array.isArray(list)) return;
    list.forEach(ov => {
      try {
        document.querySelectorAll(ov.css_selector).forEach(el => {
          if (ov.is_html == 1 || ov.is_html === true) el.innerHTML = ov.content; else el.textContent = ov.content;
        });
      } catch (_) {}
    });
  }

  async function applyContentOverrides() {
    try {
      const path = location.pathname;
      const res = await fetch('/api/content-overrides?path=' + encodeURIComponent(path), { credentials: 'same-origin' });
      if (!res.ok) { return; }
      let overrides = [];
      try { overrides = await res.json(); } catch (_) { overrides = []; }
      __gtecOverrides = overrides;
      applyOverridesToDom(overrides);
      // Повторно применим после полной загрузки и через небольшой таймаут
      window.addEventListener('load', () => { applyOverridesToDom(__gtecOverrides); });
      setTimeout(() => { applyOverridesToDom(__gtecOverrides); }, 800);
      // Небольшой наблюдатель мутаций, на случай отложенного рендера
      try {
        const mo = new MutationObserver(Utils.debounce(() => applyOverridesToDom(__gtecOverrides), 120));
        mo.observe(document.documentElement, { childList: true, subtree: true });
        // Отключим наблюдатель через 5 секунд, чтобы не держать его постоянно
        setTimeout(() => mo.disconnect(), 5000);
      } catch (_) {}
    } catch (_) {}
  }

  // === Auto-translate whole page to selected language (client-side) ===
  async function autoTranslatePageIfNeeded() {
    try {
      const cookieLang = (document.cookie.match(/(?:^|; )lang=([^;]+)/)||[])[1];
      const htmlTag = document.documentElement;
      if (!cookieLang || cookieLang === 'ru') return;
      if (htmlTag && (htmlTag.getAttribute('data-translated') === '1')) return;
      const allowed = ['ru','be','en','zh','fr','es','ja','hi','ar','pt','ur','bn'];
      if (!allowed.includes(cookieLang)) return;
      const original = document.documentElement.outerHTML;
      const form = new FormData();
      form.append('lang', cookieLang);
      form.append('html', original);
      const res = await fetch('/api/translate', { method: 'POST', body: form, credentials: 'same-origin' });
      if (!res.ok) { console.warn('Translate API HTTP error', res.status); return fallbackGoogleTranslate(cookieLang); }
      const text = await res.text();
      let j;
      try { j = JSON.parse(text); } catch (e) { console.warn('Translate API non-JSON', (text||'').slice(0,120)); return fallbackGoogleTranslate(cookieLang); }
      if (j && j.success && j.html) {
        document.open();
        document.write(j.html);
        document.close();
      } else {
        console.warn('Translate API bad payload', j);
        return fallbackGoogleTranslate(cookieLang);
      }
    } catch (e) { console.error('Translate failed', e); return fallbackGoogleTranslate((document.cookie.match(/(?:^|; )lang=([^;]+)/)||[])[1]||''); }
  }

  function setCookie(name, value) {
    try {
      const host = location.hostname;
      const parts = host.split('.');
      const domain = parts.length > 1 ? '.' + parts.slice(-2).join('.') : host;
      document.cookie = name + '=' + value + '; path=/';
      document.cookie = name + '=' + value + '; path=/; domain=' + domain;
    } catch(_) {}
  }

  function fallbackGoogleTranslate(lang) {
    if (!lang) return;
    const map = { 'zh': 'zh-CN' };
    const to = map[lang] || lang;
    setCookie('googtrans', '/ru/' + to);
    // Если виджет уже есть — дергаем его инициализацию, иначе перезагрузка
    setTimeout(() => {
      if (window.google && window.google.translate && typeof window.googleTranslateElementInit === 'function') {
        try { window.googleTranslateElementInit(); } catch(_) {}
      } else {
        location.reload();
      }
    }, 200);
  }

  // Экспорт и различные точки запуска
  window.autoTranslatePageIfNeeded = autoTranslatePageIfNeeded;
  try { autoTranslatePageIfNeeded(); } catch(_) {}
  document.addEventListener('DOMContentLoaded', () => { try { autoTranslatePageIfNeeded(); } catch(_) {} });
  window.addEventListener('load', () => { try { autoTranslatePageIfNeeded(); } catch(_) {} });
})(); 