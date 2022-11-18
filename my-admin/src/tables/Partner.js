import { ListGuesser,
    FieldGuesser, 
    ResourceGuesser, 
    InputGuesser, 
    CreateGuesser,
    ShowGuesser, } from "@api-platform/admin";
import { TextField , ReferenceField, ReferenceInput, AutocompleteInput, ArrayField, SingleFieldList, ChipField, NumberField, EmailField} from "react-admin";

const PartnerList = (props) => (
    <ListGuesser {...props}>
        <FieldGuesser source="name" />
    </ListGuesser>
    );

function Partner(){
    return {
        list: PartnerList,
    }
}
export default Partner;