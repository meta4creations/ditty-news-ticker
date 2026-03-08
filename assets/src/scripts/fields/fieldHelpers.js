/**
 * Check field visibility based on other field values
 * @param {object} field
 * @returns
 */
export const showField = (field, values) => {
  // Support both show and condition formats
  const showConfig = field.show || (field.condition ? {
    fields: [{ key: field.condition.field, compare: '=', value: field.condition.value }],
  } : null);
  if (!showConfig) {
    return true;
  }

  let formattedValues = Array.isArray(values) ? {} : values;
  if (Array.isArray(values)) {
    values.map((val) => {
      if (val.id) {
        formattedValues[val.id] = val.value ? val.value : "";
      }
    });
  }

  const operators = {
    "=": (a, b) => {
      return a === b;
    },
    "!=": (a, b) => {
      return a !== b;
    },
  };

  const relation = showConfig.relation ? showConfig.relation : "AND";
  const checks = showConfig.fields.map((f) => {
    if (operators[f.compare](formattedValues[f.key], f.value)) {
      return "pass";
    } else {
      return "fail";
    }
  });

  if ("OR" === relation) {
    return checks.includes("pass");
  } else {
    return checks.every((v) => v === "pass");
  }
};
