import type { JSX } from "react/jsx-runtime";
import { ModuleCard } from "../components/ModuleCard/ModuleCard";
import { useFetch } from "../utilities/useFetch";
import { SideBar } from "../components/Side-Bar/SideBar";
import { useState } from "react";

export function Module(): JSX.Element {
  const { data: modules, loading } = useFetch("module");
  const [selectedCat, setSelectedCat] = useState("Tous");
  const [selectedDiff, setSelectedDiff] = useState("Tous");

  const filteredModules = modules
    .filter(
      (mod) => selectedCat === "Tous" || selectedCat === mod.categorie.name,
    )
    .filter(
      (mod) => selectedDiff === "Tous" || selectedDiff === mod.difficulty,
    );

  if (loading) return <p>Chargement...</p>;

  return (
    <>
      <SideBar onCatChange={setSelectedCat} onDiffChange={setSelectedDiff} />
      <ModuleCard modules={filteredModules} />
    </>
  );
}
