import React, { FC, useCallback, useEffect, useState } from "react";

const Button: FC = () => {
    
    const [hovered, setHovered] = useState(false);
    const [oldColor, setOldColor] = useState(9);
    const [color, setColor] = useState("");

    const colorTab = ["basicVert","basicBleu", "basicRose", "basicOrange", "basicJaune"];

    const doColor = async () =>{
        //POUR QUOI ON NE RENTRE PAS ICI ???
        let colorValue = Math.floor(Math.random() * (colorTab.length ));
        if(colorValue === oldColor){
            colorValue = Math.floor(Math.random() * (colorTab.length ));
        }
        setOldColor(() => colorValue);
        setColor(colorTab[colorValue]);
    }

    // useEffect(() => console.log('useEfeffect',oldColor), [oldColor]);

    const mouseChange = useCallback(() => {
        setHovered(!hovered);
        doColor();
    }, []);
    
    useEffect(() =>{
        doColor();
    }, [])


    return(
        <a className={"spotifyConnexion connect " + color} href={process.env.REACT_APP_BASE_URL + "/oauth"} onMouseEnter={mouseChange} onMouseLeave={mouseChange}>
            <svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.668 7C12.802 7 9.66797 3.86599 9.66797 4.17233e-07" stroke="black" stroke-width="0.8"/>
                <path d="M9.66797 14C9.66797 10.134 12.802 7 16.668 7" stroke="black" stroke-width="0.8"/>
                <path d="M16.6667 7L0 7" stroke="black" stroke-width="0.8"/>
            </svg>
    
            Connexion à <span> Spotify</span>
        </a>
    );
}

export default Button;