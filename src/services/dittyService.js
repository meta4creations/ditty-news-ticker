export const updateDisplayOptions = (dittyEl, displayType, option, value) => {
  dittyEl["_ditty_" + displayType].options(option, value);
};
