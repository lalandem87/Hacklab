import { HeroHome } from "../components/Hero-Home/Hero-Home";
import { BarreInfo } from "../components/Barre-Info/Barre-Info";
import { Categories } from "../components/Categories/Categorie";
import { CatalogueModule } from "../components/CatalogueModule/CatalogueModule";

export function Home() {
  return (
    <>
      <HeroHome />
      <BarreInfo />
      <Categories />
      <CatalogueModule />
    </>
  );
}
