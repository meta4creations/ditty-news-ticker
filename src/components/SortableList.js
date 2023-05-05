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
  verticalListSortingStrategy,
} from "@dnd-kit/sortable";
import { restrictToVerticalAxis } from "@dnd-kit/modifiers";
import { useState } from "@wordpress/element";
import classnames from "classnames";
import SortableItem from "./SortableItem";

const SortableList = ({ items, onSortEnd, className }) => {
  const [isDragging, setIsDragging] = useState();
  const sensors = useSensors(
    useSensor(PointerSensor, { activationConstraint: { distance: 5 } })
    // useSensor(KeyboardSensor, {
    //   coordinateGetter: sortableKeyboardCoordinates,
    // })
  );

  /**
   * Update the index of list items
   * @param {array} event
   */
  function handleDragStart(event) {
    setIsDragging(true);
  }

  /**
   * Update the index of list items
   * @param {array} event
   */
  function handleDragEnd(event) {
    setIsDragging(false);
    const { active, over } = event;
    if (active.id !== over.id) {
      const oldIndex = items.map((i) => i.id).indexOf(active.id);
      const newIndex = items.map((i) => i.id).indexOf(over.id);
      const updatedItems = arrayMove(items, oldIndex, newIndex);
      onSortEnd(updatedItems);
    }
  }

  const classes = classnames("ditty-list ditty-list--sortable", className, {
    "is-dragging": isDragging,
  });

  return (
    <div className={classes}>
      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragStart={handleDragStart}
        onDragEnd={handleDragEnd}
        modifiers={[restrictToVerticalAxis]}
      >
        <SortableContext
          items={items.map((i) => i?.id)}
          strategy={verticalListSortingStrategy}
        >
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
