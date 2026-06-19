import type { JSX } from "react/jsx-runtime";
import { Search } from "lucide-react";
import React, { useState } from "react";
import "./SearchBar.scss";

interface SearchBarProps {
  onSearch: (term: string) => void;
}

export function SearchBar({ onSearch }: SearchBarProps): JSX.Element {
  const [searchTerm, setSearchTerm] = useState("");

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearchTerm(e.target.value);
    onSearch(e.target.value);
  };
  return (
    <div className="search-bar">
      <Search />
      <input
        type="text"
        name="sidebar"
        id="sidebar"
        value={searchTerm}
        onChange={handleChange}
        placeholder="Rechercher un module, une technique..."
      />
    </div>
  );
}
