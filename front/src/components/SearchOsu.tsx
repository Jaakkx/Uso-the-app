import React, { useState } from "react";
import { getOsuPseudo } from "../api";
import search from "../assets/search.svg";

export type Props = {
    onSuccess:(data:any)=>void;
}

class SearchOsu extends React.Component<Props>{
    state = {
        pseudo: "",
        musiquesList: [],
        oldColor:0,
        hovered:false,
        color:"rose",
        idTab: [""],
        ok:true,
        linkAvatar:"",
        beatmap:"",
        realPseudo:"",
    }

    color = (ok:boolean, id:string) => {
        let color = ["vert","bleu", "rose", "orange", "jaune"];
        let colorValue = Math.floor(Math.random() * (color.length ));

        if(ok){
            if(this.state.hovered){
                if(colorValue == this.state.oldColor){
                    colorValue = Math.floor(Math.random() * (color.length ));
                }else{
                    this.setState({oldColor:colorValue});
                    this.setState({color : color[colorValue]});
                    document.getElementById("number"+id)!.className = "music music1 "+this.state.color;
                }
            }
        }
    }

    onMouseEnter = (ok:boolean,id:string) => {
        this.setState({ hovered: true });
        this.color(ok, id);
    };
    
    onMouseLeave = (ok:boolean,id:string) => {
        this.setState({ hovered: false });
        this.color(ok, id);
    };

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ pseudo: e.currentTarget.value});
    }

    onClick = (id:string, number:string) => {  

        this.setState({idTab:[]});
        let interTab = this.state.idTab;
        interTab.push(id);
        this.setState({idTab:interTab});
        this.props.onSuccess({idTab:interTab});
        document.getElementById("number"+number)!.className = "display-none";
        console.log(document.getElementById("number"+number));
        
    }
    
    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        
        const { pseudo } = this.state;
        try {
            
            this.setState({musiquesList:[]});
            const login = await getOsuPseudo({ pseudo });
            this.setState({musiquesList: login});
            this.setState({linkAvatar:this.state.musiquesList[1]["avatar"]});
            this.setState({beatmap:this.state.musiquesList[1]["baetmapsCount"]});
            this.setState({realPseudo:this.state.pseudo});  
            console.log(this.state.musiquesList[0]);
              
            
        } catch (error) {
            alert(error);
        }
    }

    render() {

        return(
            <div className="bloc2">
                <div className="header">
                    <h2>Musiques Osu</h2>
                    <form
                        onSubmit={this.handleSubmit}
                        className="osuForm"
                    >
                        <input
                            name="osuPseudo"
                            type="text"
                            className="osuPseudo"
                            placeholder="Pseudo Joueur"
                            onChange={this.onChange}
                        >
                        </input>
                        <button type="submit" className={"submitButton "} >                        
                            <img src={search} alt="Search Icone"/>
                        </button>
                    </form>
                </div>
                <div>
                {
                    <div className="avatarParent">
                        <img src={this.state.linkAvatar} className={this.state.linkAvatar ? "Avatar":""} alt=""></img> 
                        <div>
                            <p>
                                {
                                    this.state.realPseudo                               
                                }
                            </p>
                            <p>
                                {this.state.beatmap ? "Nombre de Beatmaps jouées: " : ""}
                                <span >{this.state.beatmap}</span>
                            </p>
                        </div>
                    </div>    
                }
                </div>
                <div className="scroll2">
                    {
                        Object.keys(this.state.musiquesList[0] ? this.state.musiquesList[0]:"").map((key) => 
                           
                        <div 
                                key={key} 
                                className={"music music1 "} 
                                id={"number"+this.state.musiquesList[0][key]["id"]}
                                onClick={() => {                                    
                                    this.onClick(this.state.musiquesList[0][key],this.state.musiquesList[0][key]["id"])
                                    // this.onMouseEnter(false)
                                } 
                                } 
                                onMouseEnter={() => {

                                    if(document.querySelector("#number"+this.state.musiquesList[0][key]["id"]+".display-none") === null){
                                        this.onMouseEnter(true, this.state.musiquesList[0][key]["id"]);
                                    }else{
                                        this.onMouseEnter(false, this.state.musiquesList[0][key]["id"]);
                                    }
                                }}

                                onMouseLeave={() => {

                                    if(document.querySelector("#number"+this.state.musiquesList[0][key]["id"]+".display-none") === null){
                                        this.onMouseLeave(true, this.state.musiquesList[0][key]["id"]);
                                    }else{
                                        this.onMouseLeave(false, this.state.musiquesList[0][key]["id"]);
                                    }                                    
                                }}
                            >
                                {
                                    this.state.musiquesList[0][key]["title"]
                                }
                            </div>
                        )
                    }
                </div>
            </div>
        )
    }

}

export default SearchOsu;  