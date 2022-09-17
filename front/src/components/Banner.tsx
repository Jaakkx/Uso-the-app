



// A REFAIRE





import React, { FC, useCallback, useEffect, useState } from "react";
import { ReactComponent as Logo } from '../assets/uso.svg';
import menu from '../assets/menu.svg';
import { Link } from "react-router-dom";

const Banner: FC = () => {

    const [hovered, setHovered] = useState(false);
    const [color, setColor] = useState("vert");
    const [oldColor, setOldColor] = useState(0);

    const doColor = () => {        
        let colorTab = ["vert","bleu", "rose", "orange", "jaune"];
        let colorValue = Math.floor(Math.random() * (colorTab.length ));        
        if(hovered){
            if(colorValue === oldColor){
                colorValue = Math.floor(Math.random() * (colorTab.length ));
            }else{
                setColor(colorTab[colorValue]);
                setOldColor(colorValue);
            }
        }
        // return(colorTab[colorValue]);
        
    }
    // useEffect(() => console.log(color), [color]);

    const mouseChange = useCallback(() => {
        setHovered(!hovered);
        // console.log(doColor());
        doColor();
    }, []);


    useEffect(()=>{
        doColor();        
    }, [])

    return(
        <nav className="navbar">
            <Link to="/" className="logo">
                <Logo />
            </Link>
            <a href="" className="logo">
            </a>
            <div className="nav-links" id="nav-links">
                <div className="link-background">
                    <a className={"left " + color} href={process.env.REACT_APP_BASE_URL} onMouseEnter={mouseChange} onMouseLeave={mouseChange}>Menu</a>
                    <img src={menu} alt="Icone Menu"/>
                </div>
            </div>
        </nav>
    )
}

export default Banner;