const { useState, useEffect } = wp.element;

/**
 * Custom hook to debounce a value
 *
 * Returns a debounced version of the value that only updates after the specified delay.
 * Useful for delaying expensive operations like API calls or preview refreshes.
 *
 * @param {*} value - The value to debounce
 * @param {number} delay - The delay in milliseconds (default: 300)
 * @returns {*} The debounced value
 *
 * @example
 * const debouncedSearchTerm = useDebounce(searchTerm, 500);
 * useEffect(() => {
 *   // This will only run 500ms after the user stops typing
 *   fetchResults(debouncedSearchTerm);
 * }, [debouncedSearchTerm]);
 */
export function useDebounce(value, delay = 300) {
  const [debouncedValue, setDebouncedValue] = useState(value);

  useEffect(() => {
    // Set up the timeout
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);

    // Clean up the timeout if value changes before delay expires
    return () => {
      clearTimeout(handler);
    };
  }, [value, delay]);

  return debouncedValue;
}

export default useDebounce;
