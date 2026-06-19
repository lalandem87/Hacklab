import type { JSX } from "react/jsx-runtime";
import { ModuleCard } from "../../components/ModuleCard/ModuleCard";
import { useFetch } from "../../utilities/useFetch";
import { SideBar } from "../../components/Side-Bar/SideBar";
import { SearchBar } from "../../components/Search-Bar/SearchBar";
import { useState } from "react";
import "./Module.scss";

export function Module(): JSX.Element {
  const { data: modules, loading } = useFetch("module");
  const [selectedCat, setSelectedCat] = useState("Tous");
  const [selectedDiff, setSelectedDiff] = useState("Tous");
  const [searchTerm, setSearchTerm] = useState("");

  const filteredModules = modules
    .filter(
      (mod) => selectedCat === "Tous" || selectedCat === mod.categorie.name,
    )
    .filter((mod) => selectedDiff === "Tous" || selectedDiff === mod.difficulty)
    .filter((mod) => mod.name.toLowerCase().includes(searchTerm.toLowerCase()));

  if (loading) return <p>Chargement...</p>;

  return (
    <section className="page-module">
      <SideBar onCatChange={setSelectedCat} onDiffChange={setSelectedDiff} />
      <SearchBar onSearch={setSearchTerm} />
      <ModuleCard modules={filteredModules} />
    </section>
  );
}
