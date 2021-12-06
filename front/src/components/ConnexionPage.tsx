import React from "react";
import Banner from "./Banner";
import Button from "./Button";
import TitreConnexion from "./TitreConnexion";

const ConnexionPage = () => {

    return(
        <div>
            <Banner />
            <div className="blocConnect">
                <TitreConnexion />
                <Button />
            </div>
        </div>
    )

}

export default ConnexionPage;