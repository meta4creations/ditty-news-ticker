import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import classnames from "classnames";
import ButtonGroup from "../ButtonGroup";
import Button from "../Button";
import { TextField } from "../../fields";

const Filter = ({
  data,
  filters,
  filterKey,
  searchKey,
  searchLabel,
  className,
  onUpdate,
}) => {
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedFilter, setSelectedFilter] = useState(null);
  const classes = classnames("ditty-filter", className);

  /**
   * Filter the data
   * @param {string} filter
   */
  const filterResults = (filter) => {
    const updatedFilter = selectedFilter === filter ? null : filter;
    setSelectedFilter(updatedFilter);

    let filteredData = data;
    if (updatedFilter) {
      filteredData = data.filter((d) => d[filterKey] === updatedFilter);
    }
    if (searchQuery) {
      filteredData = filteredData.filter((d) =>
        d[searchKey].toLowerCase().includes(searchQuery.toLowerCase())
      );
    }

    onUpdate(filteredData);
  };

  /**
   * Search the data
   * @param {string} search
   */
  const searchResults = (updatedSearch) => {
    setSearchQuery(updatedSearch);

    let filteredData = data;
    if (selectedFilter) {
      filteredData = data.filter((d) => d[filterKey] === selectedFilter);
    }
    if (updatedSearch) {
      filteredData = filteredData.filter((d) =>
        d[searchKey].toLowerCase().includes(updatedSearch.toLowerCase())
      );
    }

    onUpdate(filteredData);
  };

  return (
    <div className={classes}>
      <div className="ditty-filter__search">
        <TextField
          id="dittyFilterSearch"
          name={searchLabel ? searchLabel : __("Search", "ditty-news-ticker")}
          value={searchQuery}
          onChange={searchResults}
        />
      </div>
      <div className="ditty-filter__filters">
        <ButtonGroup gap="2px">
          {filters.map((filter) => {
            const className = selectedFilter === filter.id ? "active" : "";
            return (
              <Button
                key={filter.id}
                className={className}
                onClick={() => filterResults(filter.id)}
              >
                {filter.icon
                  ? filter.icon
                  : filter.label
                  ? filter.label
                  : filter.id}
              </Button>
            );
          })}
        </ButtonGroup>
      </div>
    </div>
  );
};
export default Filter;
