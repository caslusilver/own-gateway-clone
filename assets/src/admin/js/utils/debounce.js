/**
 * Debounce function that will execute 'func' after 'delay'
 *
 * @param {*} func
 * @param {*} delay
 * @returns
 */
export function debounce(func, delay = 300) {
  let timer;

  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => func.apply(this, args), delay);
  };
}
