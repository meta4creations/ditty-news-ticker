import {
  DndContext,
  useSensor,
  useSensors,
  PointerSensor,
  KeyboardSensor,
  closestCenter,
} from "@dnd-kit/core";
import {
  SortableContext,
  sortableKeyboardCoordinates,
  arrayMove,
} from "@dnd-kit/sortable";
import SortableItem from "./SortableItem";

const SortableList = ({ items, onSortEnd }) => {
  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } }),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  /**
   * Update the index of list items
   * @param {array} event
   */
  function handleDragEnd(event) {
    const { active, over } = event;
    if (active.id !== over.id) {
      const oldIndex = items.map((i) => i.id).indexOf(active.id);
      const newIndex = items.map((i) => i.id).indexOf(over.id);
      const updatedItems = arrayMove(items, oldIndex, newIndex);
      onSortEnd(updatedItems);
    }
  }

  return (
    <div className="ditty-list ditty-list--sortable">
      <DndContext
        onDragEnd={handleDragEnd}
        sensors={sensors}
        collisionDetection={closestCenter}
      >
        <SortableContext items={items.map((i) => i?.id)}>
          {items.map((value, index) => {
            return (
              <SortableItem key={value.id} id={value?.id} index={index}>
                {value.content}
              </SortableItem>
            );
          })}
        </SortableContext>
      </DndContext>
    </div>
  );
};

export default SortableList;
