import { HeroHome } from "../components/Hero-Home/Hero-Home";
import { BarreInfo } from "../components/Barre-Info/Barre-Info";
import { Categories } from "../components/Categories/Categorie";

export function Home() {
  return (
    <>
      <HeroHome />
      <BarreInfo />
      <Categories />
    </>
  );
}
