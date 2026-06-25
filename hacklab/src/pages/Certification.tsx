import type { JSX } from "react/jsx-runtime";
import { HeroCertification } from "../components/Hero-Certification/Hero-Certification";
import { CertifCards } from "../components/Certif-Card/Certif-Cards";

export function Certification(): JSX.Element {
  return (
    <>
      <HeroCertification />
      <CertifCards />
    </>
  );
}
