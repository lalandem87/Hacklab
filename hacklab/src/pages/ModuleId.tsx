import type { JSX } from "react/jsx-runtime";
import { ModuleRoom } from "../components/ModuleRoom/ModuleRoom";

export function ModuleId(): JSX.Element {
  return (
    <section className="module-id">
      <ModuleRoom />
    </section>
  );
}
