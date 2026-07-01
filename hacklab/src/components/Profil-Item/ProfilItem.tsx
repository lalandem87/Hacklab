import type { JSX } from "react/jsx-runtime";
import "./ProfilItem.scss";
import { ArrowRight } from "lucide-react";

interface ProfilItemProps {
  classname: string;
  subclass: string;
  classname2: string;
  title: string;
  data: any[];
  renderItem: (d: any) => JSX.Element;
}

export function ProfilItem({
  classname,
  subclass,
  classname2,
  title,
  data,
  renderItem,
}: ProfilItemProps): JSX.Element {
  return (
    <>
      <div className={classname}>
        <div className="top">
          <h3>{title}</h3>
          <button>
            Tout voir <ArrowRight />
          </button>
        </div>
        <div className={`container-${subclass}`}>
          {data.map((d) => {
            return (
              <div key={d.id} className={classname2}>
                {renderItem(d)}
              </div>
            );
          })}
        </div>
      </div>
    </>
  );
}
