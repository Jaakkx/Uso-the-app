import React from "react";
import { getOsuPseudo } from "../api";
import search from "../assets/search.svg"

export type State = {
    pseudo: string,
    musiquesList: string[],
};

class SearchOsu extends React.Component{
    state = {
        pseudo: "",
        musiquesList: [],
    }

    onChange = (e: React.FormEvent<HTMLInputElement>): void =>{
        this.setState({ pseudo: e.currentTarget.value});
    }

    handleSubmit = async(e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const { pseudo } = this.state;

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
                            <div key={item['titre']} className="music">
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
