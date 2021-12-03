import React from "react";
import { getOsuPseudo } from "../api";
import search from "../assets/search.svg"

export type State = {
    pseudo: string,
    musiquesList: string[],
    musiqueChoose: string[],
};

class SearchOsu extends React.Component{
    state = {
        pseudo: "",
        musiquesList: [],
        oldColor:0,
        hovered:false,
        color:"",
        idTab: [],
    }

    color = () => {
        let color = ["vert","bleu", "rose", "orange", "jaune"];
        let colorValue = Math.floor(Math.random() * (color.length ));

        if(this.state.hovered){
            if(colorValue == this.state.oldColor){
                colorValue = Math.floor(Math.random() * (color.length ));
            }else{
                this.setState({oldColor:colorValue});
                this.setState({color : color[colorValue]});
            }
        }
    }

    onMouseEnter(){
        this.setState({ hovered: true });
        this.color();
    };
    
    onMouseLeave = () => {
        this.setState({ hovered: false });
        this.color();
    };

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ pseudo: e.currentTarget.value});
    }

    onClick = (id:string) => {
        // this.setState(idTab)
    }

    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const { pseudo } = this.state;
        console.log(pseudo);

        try {

            this.setState({musiquesList:[]});
            const login = await getOsuPseudo({ pseudo });
            this.setState({musiquesList: login});

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

                <div className="scroll2">
                    {
                        this.state.musiquesList.map(item => (
                            <div key={item['id']} className={"music " + this.state.color} onMouseEnter={() => this.onMouseEnter()} onMouseLeave={() => this.onMouseLeave()}>
                                {item['titre']}
                            </div>
                        ))
                    }
                </div>
            </div>
        )
    }

}

export default SearchOsu;  
